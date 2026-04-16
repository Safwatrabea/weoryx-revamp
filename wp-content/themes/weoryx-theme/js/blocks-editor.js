(function (wp) {
    const { registerBlockType } = wp.blocks;
    const { InspectorControls, useBlockProps, MediaUpload, MediaUploadCheck } = wp.blockEditor;
    const { PanelBody, TextControl, TextareaControl, Placeholder, Spinner, Button, Dashicon, SelectControl } = wp.components;
    const { createElement: el, Fragment, useEffect } = wp.element;

    // Robust ServerSideRender detection
    const ServerSideRender = wp.serverSideRender || (wp.editor ? wp.editor.ServerSideRender : null);

    /**
     * Helper to resolve media URL (handles relative paths for demo data)
     */
    function resolveMediaUrl(url) {
        if (!url) return '';
        if (url.startsWith('http') || url.startsWith('blob') || url.startsWith('data:')) {
            return url;
        }
        // Fallback to theme images directory
        const baseUrl = (window.weoryxData && window.weoryxData.templateUrl) ? window.weoryxData.templateUrl : '';
        return baseUrl + '/images/' + url;
    }

    /**
     * Helper function to render a Media Upload field
     */
    function renderMediaField(label, value, onSelect, onRemove, allowedTypes = ['image', 'video']) {
        const displayUrl = resolveMediaUrl(value);
        return el('div', { style: { marginBottom: '15px' } },
            el('label', { style: { display: 'block', marginBottom: '5px', fontWeight: '500' } }, label),
            el(MediaUploadCheck, {},
                el(MediaUpload, {
                    onSelect: (media) => onSelect(media.url),
                    allowedTypes: allowedTypes,
                    value: value,
                    render: ({ open }) => el('div', { style: { display: 'flex', flexDirection: 'column', gap: '8px' } },
                        value ? el('div', { style: { position: 'relative', border: '1px solid #ddd', borderRadius: '4px', overflow: 'hidden', background: '#eee' } },
                            allowedTypes.includes('video') && displayUrl.match(/\.(mp4|webm|ogg)$/)
                                ? el('video', { src: displayUrl, style: { width: '100%', maxHeight: '100px', display: 'block' } })
                                : el('img', { src: displayUrl, style: { width: '100%', maxHeight: '100px', display: 'block', objectFit: 'cover' } }),
                            el(Button, {
                                isDestructive: true,
                                isSmall: true,
                                icon: 'no-alt',
                                onClick: onRemove,
                                style: { position: 'absolute', top: '5px', right: '5px' }
                            })
                        ) : null,
                        el(Button, {
                            isPrimary: true,
                            onClick: open,
                            icon: 'upload'
                        }, value ? 'Change Media' : 'Select Media')
                    )
                })
            )
        );
    }

    /**
     * Helper function to register blocks with ServerSideRender and Sidebar Controls
     */
    function createWeoryxBlock(blockName, blockTitle, blockIcon, attributesConfig, controlsConfig, extraConfig = {}) {
        registerBlockType('weoryx/' + blockName, {
            apiVersion: 2,
            title: blockTitle,
            icon: blockIcon,
            category: 'weoryx',
            attributes: attributesConfig,
            ...extraConfig,
            edit: function (props) {
                const { attributes, setAttributes } = props;
                const blockProps = useBlockProps();

                // Re-initialize interactive scripts after SSR content updates
                useEffect(() => {
                    window.weoryx_is_editor = true;
                    const timer = setTimeout(() => {
                        if (window.weoryxInitAll) {
                            window.weoryxInitAll();
                        }
                    }, 500); // Small delay to let SSR render the DOM
                    return () => clearTimeout(timer);
                }, [attributes]);

                // Helper to update repeater items
                const updateItem = (key, index, field, value) => {
                    const newItems = [...(attributes[key] || [])];
                    if (typeof newItems[index] !== 'object' || newItems[index] === null) {
                        newItems[index] = { [field]: value };
                    } else {
                        newItems[index] = { ...newItems[index], [field]: value };
                    }
                    setAttributes({ [key]: newItems });
                };

                const addItem = (key, defaultItem) => {
                    const newItems = [...(attributes[key] || []), { ...defaultItem }];
                    setAttributes({ [key]: newItems });
                };

                const removeItem = (key, index) => {
                    const newItems = (attributes[key] || []).filter((_, i) => i !== index);
                    setAttributes({ [key]: newItems });
                };

                return el(Fragment, {},
                    // Sidebar Controls
                    controlsConfig ? el(InspectorControls, {},
                        el(PanelBody, { title: '⚙️ ' + blockTitle + ' Configuration', initialOpen: true },
                            controlsConfig.map(function (control) {
                                if (control.type === 'repeater') {
                                    const items = attributes[control.key] || [];
                                    return el('div', { style: { marginBottom: '20px', borderTop: '1px solid #ddd', paddingTop: '10px' }, key: control.key },
                                        el('label', { style: { fontWeight: 'bold', display: 'block', marginBottom: '10px' } }, control.label),
                                        items.map((item, index) => el('div', {
                                            key: index,
                                            style: {
                                                padding: '10px',
                                                background: '#f9f9f9',
                                                border: '1px solid #eee',
                                                marginBottom: '10px',
                                                position: 'relative'
                                            }
                                        },
                                            control.fields.map(field => {
                                                if (field.type === 'media') {
                                                    return renderMediaField(
                                                        field.label,
                                                        item[field.key],
                                                        (url) => updateItem(control.key, index, field.key, url),
                                                        () => updateItem(control.key, index, field.key, ''),
                                                        field.allowedTypes || ['image']
                                                    );
                                                }
                                                const fieldComponent = field.type === 'textarea' ? TextareaControl : TextControl;
                                                return el(fieldComponent, {
                                                    label: field.label,
                                                    value: item[field.key] || '',
                                                    onChange: (val) => updateItem(control.key, index, field.key, val)
                                                });
                                            }),
                                            el(Button, {
                                                isDestructive: true,
                                                isSmall: true,
                                                onClick: () => removeItem(control.key, index),
                                                style: { marginTop: '5px' }
                                            }, 'Remove Item')
                                        )),
                                        el(Button, {
                                            isPrimary: true,
                                            onClick: () => addItem(control.key, control.defaultItem || {})
                                        }, '➕ Add ' + (control.itemLabel || 'Item'))
                                    );
                                }

                                const value = attributes[control.key] || (attributesConfig[control.key] ? attributesConfig[control.key].default : '');

                                if (control.type === 'media') {
                                    return renderMediaField(
                                        control.label,
                                        value,
                                        (url) => setAttributes({ [control.key]: url }),
                                        () => setAttributes({ [control.key]: '' }),
                                        control.allowedTypes || ['image']
                                    );
                                }

                                if (control.type === 'select') {
                                    return el('div', { style: { marginBottom: '15px' }, key: control.key },
                                        el(SelectControl, {
                                            label: control.label,
                                            value: value,
                                            options: control.options,
                                            onChange: (val) => setAttributes({ [control.key]: val })
                                        })
                                    );
                                }

                                if (control.type === 'text') {
                                    return el('div', { style: { marginBottom: '15px' }, key: control.key },
                                        el(TextControl, {
                                            label: control.label,
                                            value: value,
                                            onChange: (val) => {
                                                setAttributes({ [control.key]: val });
                                            },
                                            placeholder: control.placeholder || ''
                                        })
                                    );
                                } else if (control.type === 'textarea') {
                                    return el('div', { style: { marginBottom: '15px' }, key: control.key },
                                        el(TextareaControl, {
                                            label: control.label,
                                            value: value,
                                            onChange: (val) => {
                                                setAttributes({ [control.key]: val });
                                            },
                                            placeholder: control.placeholder || '',
                                            rows: control.rows || 3
                                        })
                                    );
                                } else if (control.type === 'number') {
                                    return el('div', { style: { marginBottom: '15px' }, key: control.key },
                                        el(TextControl, {
                                            label: control.label,
                                            type: 'number',
                                            value: value,
                                            onChange: (val) => {
                                                setAttributes({ [control.key]: parseInt(val) || 0 });
                                            },
                                            min: control.min || 1,
                                            max: control.max || 100
                                        })
                                    );
                                }
                            }),
                            el('div', {
                                style: {
                                    padding: '12px',
                                    background: '#f0f6fb',
                                    borderRadius: '6px',
                                    marginTop: '15px',
                                    border: '1px solid #ddecf5'
                                }
                            },
                                el('p', { style: { margin: 0, fontSize: '12px', color: '#1e8cbe', fontWeight: '500' } },
                                    '✨ WYSIWYG Mode: All changes reflect immediately in the preview.'
                                )
                            )
                        )
                    ) : null,

                    // Block Preview
                    el('div', { ...blockProps, className: 'weoryx-editor-block-wrapper ' + (blockProps.className || '') },
                        ServerSideRender ? el(ServerSideRender, {
                            block: 'weoryx/' + blockName,
                            attributes: attributes
                        }) : el(Placeholder, { icon: blockIcon, label: blockTitle + ' (Loading...)' }, el(Spinner))
                    )
                );
            },
            save: function () { return null; }
        });
    }

    // --- Block Registrations ---

    // Hero Section
    createWeoryxBlock('hero', 'Hero Section', 'cover-image',
        { slides: { type: 'array', default: [] } },
        [
            {
                type: 'repeater',
                key: 'slides',
                label: '🚀 Hero Slides',
                itemLabel: 'Slide',
                fields: [
                    { key: 'tag', label: 'Tagline' },
                    { key: 'title', label: 'Title (use <br> for break)' },
                    { key: 'description', label: 'Description', type: 'textarea' },
                    { key: 'imageUrl', label: 'Image', type: 'media' },
                    { key: 'buttonText', label: 'Button 1 Text' },
                    { key: 'buttonUrl', label: 'Button 1 URL' },
                    { key: 'button2Text', label: 'Button 2 Text (Optional)' },
                    { key: 'button2Url', label: 'Button 2 URL' }
                ],
                defaultItem: {
                    tag: 'Agency',
                    title: 'New Headline',
                    description: 'Description...',
                    imageUrl: 'hero-person-1.png',
                    buttonText: 'Join Us',
                    buttonUrl: '#',
                    button2Text: '',
                    button2Url: '#'
                }
            }
        ]
    );

    // Services
    createWeoryxBlock('services', 'Services', 'grid-view',
        {
            tag: { type: 'string', default: '' },
            title: { type: 'string', default: '' },
            description: { type: 'string', default: '' },
            count: { type: 'number', default: 6 }
        },
        [
            { type: 'text', key: 'tag', label: '🏷️ Tag' },
            { type: 'text', key: 'title', label: '📝 Title' },
            { type: 'textarea', key: 'description', label: '📄 Description', rows: 4 },
            { type: 'number', key: 'count', label: '🔢 Number of Services' }
        ]
    );

    // About
    createWeoryxBlock('about', 'About', 'info',
        {
            tag: { type: 'string', default: '' },
            title: { type: 'string', default: '' },
            description: { type: 'string', default: '' },
            buttonText: { type: 'string', default: '' },
            buttonUrl: { type: 'string', default: '' },
            imageUrl: { type: 'string', default: '' },
            expNumber: { type: 'string', default: '' },
            expText: { type: 'string', default: '' },
            features: { type: 'array', default: [] },
            variant: { type: 'string', default: 'simple' }
        },
        [
            {
                type: 'select',
                key: 'variant',
                label: '🎨 Design Variant',
                options: [
                    { label: 'Standard (Clean)', value: 'simple' },
                    { label: 'Ultra Premium (Page)', value: 'premium' }
                ]
            },
            { type: 'text', key: 'tag', label: '🏷️ Tag' },
            { type: 'text', key: 'title', label: '📝 Title' },
            { type: 'textarea', key: 'description', label: '📖 Description' },
            { type: 'text', key: 'buttonText', label: '🔘 Button Text' },
            { type: 'text', key: 'buttonUrl', label: '🔗 Button URL' },
            { type: 'media', key: 'imageUrl', label: '🖼️ Image' },
            { type: 'text', key: 'expNumber', label: '🔢 Exp Number (e.g. 12+)' },
            { type: 'text', key: 'expText', label: '✍️ Exp Text' },
            {
                type: 'repeater',
                key: 'features',
                label: '✨ Features',
                itemLabel: 'Feature',
                fields: [
                    { key: 'icon', label: 'Icon Class (FontAwesome)' },
                    { key: 'title', label: 'Feature Title' },
                    { key: 'description', label: 'Feature Desc' }
                ],
                defaultItem: { icon: 'fas fa-check', title: 'Quality', description: 'Best in class' }
            }
        ]
    );

    // Portfolio
    createWeoryxBlock('portfolio', 'Portfolio', 'portfolio',
        {
            tag: { type: 'string', default: 'Our Work' },
            title: { type: 'string', default: 'Recent | Projects' },
            subtitle: { type: 'string', default: 'Samples of our work that helped our clients achieve their goals.' },
            buttonText: { type: 'string', default: 'See All Work' },
            buttonUrl: { type: 'string', default: '/portfolio' },
            items: { type: 'array', default: [] },
            count: { type: 'number', default: 5 }
        },
        [
            { type: 'text', key: 'tag', label: '🏷️ Tag' },
            { type: 'text', key: 'title', label: '📝 Title' },
            { type: 'textarea', key: 'subtitle', label: '📄 Subtitle' },
            { type: 'number', key: 'count', label: '🔢 Number of Projects', min: 1, max: 12 },
            { type: 'text', key: 'buttonText', label: '🔘 Button Text' },
            { type: 'text', key: 'buttonUrl', label: '🔗 Button URL' },
            {
                type: 'repeater',
                key: 'items',
                label: '🖼️ Portfolio Items (Manual Fallback)',
                itemLabel: 'Project',
                fields: [
                    { key: 'title', label: 'Project Title' },
                    { key: 'category', label: 'Category' },
                    { key: 'image', label: 'Image', type: 'media' },
                    { key: 'link', label: 'Project Link' }
                ],
                defaultItem: { title: 'New Project', category: 'Web', image: 'portfolio-1.jpg', link: '#' }
            }
        ]
    );

    // Special Offer
    createWeoryxBlock('offer', 'Special Offer', 'tag',
        {
            tag: { type: 'string', default: 'Limited Time Offer' },
            title: { type: 'string', default: 'Own Your Website Starting From 1500 SAR' },
            price: { type: 'string', default: '1500 SAR' },
            pricePrefix: { type: 'string', default: 'Starting From' },
            cardDescription: { type: 'string', default: 'Get your professional website launched in record time.' },
            cardNote: { type: 'string', default: 'Limited spots available for this month.' },
            buttonText: { type: 'string', default: 'Claim Offer Now' },
            buttonUrl: { type: 'string', default: '/contact' },
            features: {
                type: 'array',
                default: [
                    { text: 'Responsive Design' },
                    { text: 'SEO Friendly' },
                    { text: 'Fast Loading' },
                    { text: 'Secure Hosting' }
                ]
            }
        },
        [
            { type: 'text', key: 'tag', label: '🏷️ Tag' },
            { type: 'text', key: 'title', label: '📝 Title' },
            { type: 'text', key: 'pricePrefix', label: '🏷️ Price Prefix (e.g., Starting From)' },
            { type: 'text', key: 'price', label: '💰 Price' },
            { type: 'textarea', key: 'cardDescription', label: '📄 Card Description' },
            { type: 'text', key: 'cardNote', label: '📝 Card Note' },
            { type: 'text', key: 'buttonText', label: '🔘 Button Text' },
            { type: 'text', key: 'buttonUrl', label: '🔗 Button URL' },
            {
                type: 'repeater',
                key: 'features',
                label: '📋 Offer Features',
                itemLabel: 'Feature',
                fields: [
                    { key: 'text', label: 'Feature Description' }
                ],
                defaultItem: { text: 'New Feature' }
            }
        ]
    );

    // Blog Posts
    createWeoryxBlock('blog', 'Blog Posts', 'admin-post',
        {
            tag: { type: 'string', default: '' },
            title: { type: 'string', default: '' },
            subtitle: { type: 'string', default: '' },
            count: { type: 'number', default: 3 }
        },
        [
            { type: 'text', key: 'tag', label: '🏷️ Tag' },
            { type: 'text', key: 'title', label: '📝 Title' },
            { type: 'text', key: 'subtitle', label: '📄 Subtitle' },
            { type: 'number', key: 'count', label: '🔢 Number of Posts' }
        ]
    );

    // Video Section
    createWeoryxBlock('video', 'Video Section', 'video-alt3',
        {
            tag: { type: 'string', default: '' },
            title: { type: 'string', default: '' },
            description: { type: 'string', default: '' },
            thumbnail: { type: 'string', default: '' },
            videoUrl: { type: 'string', default: '' }
        },
        [
            { type: 'text', key: 'tag', label: '🏷️ Tag' },
            { type: 'text', key: 'title', label: '📝 Title' },
            { type: 'textarea', key: 'description', label: '📄 Description' },
            { type: 'media', key: 'thumbnail', label: '🖼️ Video Thumbnail' },
            { type: 'text', key: 'videoUrl', label: '🎥 YouTube Video URL' }
        ]
    );

    // Statistics
    createWeoryxBlock('stats', 'Statistics', 'chart-bar',
        {
            stats: { type: 'array', default: [] },
            variant: { type: 'string', default: 'simple' }
        },
        [
            {
                type: 'select',
                key: 'variant',
                label: '🎨 Design Variant',
                options: [
                    { label: 'Standard (Clean)', value: 'simple' },
                    { label: 'Ultra Premium (Floating Glass)', value: 'premium' }
                ]
            },
            {
                type: 'repeater',
                key: 'stats',
                label: '📊 Statistics',
                itemLabel: 'Stat',
                fields: [
                    { key: 'icon', label: 'Icon Class (e.g. fa-users)' },
                    { key: 'number', label: 'Number' },
                    { key: 'label', label: 'Label' }
                ],
                defaultItem: { icon: 'fa-users', number: '100+', label: 'Happy Clients' }
            }
        ]
    );

    // Clients
    createWeoryxBlock('clients', 'Clients', 'groups',
        {
            tag: { type: 'string', default: '' },
            title: { type: 'string', default: '' },
            clients: { type: 'array', default: [] }
        },
        [
            { type: 'text', key: 'tag', label: '🏷️ Tag' },
            { type: 'text', key: 'title', label: '📝 Title' },
            {
                type: 'repeater',
                key: 'clients',
                label: '🏢 Client Logos',
                itemLabel: 'Logo',
                fields: [
                    { key: 'img', label: 'Client Logo', type: 'media' }
                ],
                defaultItem: { img: 'client-1.svg' }
            }
        ]
    );

    // Testimonials
    createWeoryxBlock('reviews', 'Testimonials', 'star-filled',
        {
            tag: { type: 'string', default: '' },
            title: { type: 'string', default: '' },
            description: { type: 'string', default: '' }
        },
        [
            { type: 'text', key: 'tag', label: '🏷️ Tag' },
            { type: 'text', key: 'title', label: '📝 Title' },
            { type: 'textarea', key: 'description', label: '📄 Description' }
        ]
    );

    // Reels
    createWeoryxBlock('reels', 'Reels', 'format-video',
        {
            tag: { type: 'string', default: '' },
            title: { type: 'string', default: '' }
        },
        [
            { type: 'text', key: 'tag', label: '🏷️ Tag' },
            { type: 'text', key: 'title', label: '📝 Title' }
        ]
    );

    // Why Choose Us
    createWeoryxBlock('choose-us', 'Why Choose Us', 'yes-alt',
        {
            tag: { type: 'string', default: '' },
            title: { type: 'string', default: '' },
            description: { type: 'string', default: '' },
            items: { type: 'array', default: [] }
        },
        [
            { type: 'text', key: 'tag', label: '🏷️ Tag' },
            { type: 'text', key: 'title', label: '📝 Title' },
            { type: 'textarea', key: 'description', label: '📄 Description' },
            {
                type: 'repeater',
                key: 'items',
                label: '✨ Why Us Items',
                itemLabel: 'Feature',
                fields: [
                    { key: 'icon', label: 'Icon (fa-*)' },
                    { key: 'title', label: 'Feature Title' },
                    { key: 'desc', label: 'Description', type: 'textarea' }
                ],
                defaultItem: { icon: 'fa-check', title: 'Quality', desc: 'Detail...' }
            }
        ]
    );

    // Mission & Vision
    createWeoryxBlock('mission-vision', 'Mission & Vision', 'visibility',
        {
            tag: { type: 'string', default: '' },
            title: { type: 'string', default: '' },
            items: { type: 'array', default: [] }
        },
        [
            { type: 'text', key: 'tag', label: '🏷️ Tag' },
            { type: 'text', key: 'title', label: '📝 Title' },
            {
                type: 'repeater',
                key: 'items',
                label: '💡 Core Items',
                itemLabel: 'Value',
                fields: [
                    { key: 'icon', label: 'Icon (fa-*)' },
                    { key: 'title', label: 'Item Title' },
                    { key: 'desc', label: 'Item Description', type: 'textarea' }
                ],
                defaultItem: { icon: 'fa-bullseye', title: 'Mission', desc: 'Our mission is...' }
            }
        ]
    );

    // Steps
    createWeoryxBlock('steps', 'Steps', 'list-view',
        {
            tag: { type: 'string', default: '' },
            title: { type: 'string', default: '' },
            items: { type: 'array', default: [] }
        },
        [
            { type: 'text', key: 'tag', label: '🏷️ Tag' },
            { type: 'text', key: 'title', label: '📝 Title' },
            {
                type: 'repeater',
                key: 'items',
                label: '🛤️ Workflow Steps',
                itemLabel: 'Step',
                fields: [
                    { key: 'title', label: 'Step Title' },
                    { key: 'desc', label: 'Description', type: 'textarea' }
                ],
                defaultItem: { title: 'Step 1', desc: 'Process...' }
            }
        ]
    );

    // Pricing
    createWeoryxBlock('pricing', 'Pricing Table', 'grid-view',
        {
            tag: { type: 'string', default: '' },
            title: { type: 'string', default: '' },
            subtitle: { type: 'string', default: '' },
            plans: { type: 'array', default: [] }
        },
        [
            { type: 'text', key: 'tag', label: '🏷️ Tag' },
            { type: 'text', key: 'title', label: '📝 Title' },
            { type: 'textarea', key: 'subtitle', label: '📄 Subtitle' },
            {
                type: 'repeater',
                key: 'plans',
                label: '💰 Pricing Plans',
                itemLabel: 'Plan',
                fields: [
                    { key: 'name', label: 'Plan Name (e.g. Starter)' },
                    { key: 'price', label: 'Price' },
                    { key: 'period', label: 'Period (e.g. /month)' },
                    { key: 'desc', label: 'Short Description', type: 'textarea' },
                    { key: 'buttonText', label: 'Button Text' },
                    { key: 'buttonUrl', label: 'Button URL' },
                    { key: 'isFeatured', label: 'Featured (Highlight)', type: 'boolean' },
                    { key: 'badgeText', label: 'Badge (if featured)' },
                    {
                        key: 'features',
                        label: '✨ Features (comma separated)',
                        type: 'textarea',
                        placeholder: 'Feature 1, Feature 2, Feature 3'
                    }
                ],
                defaultItem: {
                    name: 'Starter',
                    price: '499',
                    period: '/month',
                    desc: 'Perfect for small businesses',
                    buttonText: 'Get Started',
                    buttonUrl: '#',
                    isFeatured: false,
                    features: 'Feature 1, Feature 2'
                }
            }
        ]
    );

    // Team
    createWeoryxBlock('team', 'Team', 'groups',
        {
            tag: { type: 'string', default: '' },
            title: { type: 'string', default: '' },
            members: { type: 'array', default: [] }
        },
        [
            { type: 'text', key: 'tag', label: '🏷️ Tag' },
            { type: 'text', key: 'title', label: '📝 Title' },
            {
                type: 'repeater',
                key: 'members',
                label: '👥 Team Members',
                itemLabel: 'Member',
                fields: [
                    { key: 'name', label: 'Member Name' },
                    { key: 'role', label: 'Member Role' },
                    { key: 'img', label: 'Member Image', type: 'media' }
                ],
                defaultItem: { name: 'John Doe', role: 'Developer', img: 'team-1.png' }
            }
        ]
    );

    // Pricing
    createWeoryxBlock('pricing', 'Pricing', 'money-alt',
        {
            tag: { type: 'string', default: '' },
            title: { type: 'string', default: '' },
            plans: { type: 'array', default: [] }
        },
        [
            { type: 'text', key: 'tag', label: '🏷️ Tag' },
            { type: 'text', key: 'title', label: '📝 Title' },
            {
                type: 'repeater',
                key: 'plans',
                label: '💰 Pricing Plans',
                itemLabel: 'Plan',
                fields: [
                    { key: 'name', label: 'Plan Name' },
                    { key: 'price', label: 'Price (e.g., 500 SAR)' },
                    { key: 'subtitle', label: 'Subtitle' },
                    { key: 'features', label: 'Features (One per line)', type: 'textarea' }
                ],
                defaultItem: { name: 'Basic', price: '500 SAR', subtitle: 'Start small', features: 'Feature 1\nFeature 2' }
            }
        ]
    );

    // Contact
    createWeoryxBlock('contact', 'Contact', 'email',
        {
            title: { type: 'string', default: '' },
            description: { type: 'string', default: '' },
            address: { type: 'string', default: '' },
            email: { type: 'string', default: '' }
        },
        [
            { type: 'text', key: 'title', label: '📝 Title' },
            { type: 'textarea', key: 'description', label: '📄 Description' },
            { type: 'text', key: 'address', label: '📍 Address' },
            { type: 'text', key: 'email', label: '📧 Email' }
        ]
    );

    // Page Header
    createWeoryxBlock('page-header', 'Page Header', 'heading',
        {
            title: { type: 'string', default: '' }
        },
        [
            { type: 'text', key: 'title', label: '📝 Title Override' }
        ]
    );

    // Call to Action
    createWeoryxBlock('cta', 'Call to Action', 'megaphone',
        {
            title: { type: 'string', default: '' },
            description: { type: 'string', default: '' },
            buttonText: { type: 'string', default: '' },
            buttonUrl: { type: 'string', default: '' }
        },
        [
            { type: 'text', key: 'title', label: '📝 Title' },
            { type: 'textarea', key: 'description', label: '📄 Description' },
            { type: 'text', key: 'buttonText', label: '🔘 Button Text' },
            { type: 'text', key: 'buttonUrl', label: '🔗 Button URL' }
        ]
    );

    // Service Request
    createWeoryxBlock('service-request', 'Service Request Form', 'email-alt',
        {
            tag: { type: 'string', default: 'Ready to Start?' },
            title: { type: 'string', default: 'Book This Service | Now' },
            description: { type: 'string', default: 'Ready to take your business to the next level? Fill out the form below and our team will get back to you with a tailored strategy.' },
            features: { type: 'array', default: [] },
            formShortcode: { type: 'string', default: '' }
        },
        [
            { type: 'text', key: 'tag', label: '🏷️ Tag' },
            { type: 'text', key: 'title', label: '📝 Title' },
            { type: 'textarea', key: 'description', label: '📄 Description' },
            { type: 'text', key: 'formShortcode', label: '📨 Form Shortcode (Optional Override)' },
            {
                type: 'repeater',
                key: 'features',
                label: '✅ Features',
                itemLabel: 'Feature',
                fields: [
                    { key: 'icon', label: 'Icon (fa-*)' },
                    { key: 'text', label: 'Text' }
                ],
                defaultItem: { icon: 'fas fa-check-circle', text: 'New Feature' }
            }
        ]
    );

    // Service Intro
    createWeoryxBlock('service-intro', 'Service Intro (Image & Text)', 'align-pull-right',
        {
            tag: { type: 'string', default: '' },
            title: { type: 'string', default: '' },
            content: { type: 'string', default: '' },
            imageUrl: { type: 'string', default: '' },
            imagePosition: { type: 'string', default: 'right' },
            buttonText: { type: 'string', default: '' },
            buttonUrl: { type: 'string', default: '' }
        },
        [
            { type: 'text', key: 'tag', label: '🏷️ Tag' },
            { type: 'text', key: 'title', label: '📝 Title' },
            { type: 'textarea', key: 'content', label: '📄 Content', rows: 6 },
            { type: 'media', key: 'imageUrl', label: '🖼️ Image' },
            {
                type: 'select',
                key: 'imagePosition',
                label: '↔️ Image Position',
                options: [
                    { label: 'Right', value: 'right' },
                    { label: 'Left', value: 'left' }
                ]
            },
            { type: 'text', key: 'buttonText', label: '🔘 Button Text' },
            { type: 'text', key: 'buttonUrl', label: '🔗 Button URL' }
        ]
    );

    // Related Portfolio
    createWeoryxBlock('related-portfolio', 'Related Portfolio', 'portfolio',
        {
            tag: { type: 'string', default: 'Portfolio' },
            title: { type: 'string', default: 'Previous | Work' },
            count: { type: 'number', default: 3 },
            categoryId: { type: 'string', default: '' }
        },
        [
            { type: 'text', key: 'tag', label: '🏷️ Tag' },
            { type: 'text', key: 'title', label: '📝 Title' },
            { type: 'number', key: 'count', label: '🔢 Number of Projects', min: 1, max: 12 },
            {
                type: 'select',
                key: 'categoryId',
                label: '📂 Portfolio Category',
                options: (window.weoryxData && window.weoryxData.portfolioCategories) ? window.weoryxData.portfolioCategories : [{ label: 'Default', value: '' }]
            }
        ],
        {
            keywords: ['portfolio', 'projects', 'work', 'services'],
            description: 'Displays related portfolio items based on the selected category.'
        }
    );

})(window.wp);

