(function (blocks, element, blockEditor, components) {
    var el = element.createElement;
    var RichText = blockEditor.RichText;
    var useBlockProps = blockEditor.useBlockProps;

    blocks.registerBlockType('weoryx/stats', {
        title: 'Stats Section',
        icon: 'chart-bar',
        category: 'weoryx',
        attributes: {
            stats: {
                type: 'array',
                default: [
                    { label: 'Happy Clients', number: '500', icon: 'fa-users' },
                    { label: 'Projects Done', number: '1200', icon: 'fa-check' }
                ]
            }
        },
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var blockProps = useBlockProps();

            function updateStat(index, key, value) {
                var newStats = [...attributes.stats];
                newStats[index] = { ...newStats[index], [key]: value };
                setAttributes({ stats: newStats });
            }

            function addStat() {
                setAttributes({ stats: [...attributes.stats, { label: '', number: '', icon: 'fa-star' }] });
            }

            return el(
                'div',
                blockProps,
                el('div', { className: 'stats-editor-grid' },
                    attributes.stats.map(function (stat, index) {
                        return el('div', { key: index, style: { border: '1px solid #eee', padding: '10px', marginBottom: '10px' } },
                            el(RichText, {
                                tagName: 'h4',
                                value: stat.number,
                                onChange: function (val) { updateStat(index, 'number', val); },
                                placeholder: 'Number'
                            }),
                            el(RichText, {
                                tagName: 'p',
                                value: stat.label,
                                onChange: function (val) { updateStat(index, 'label', val); },
                                placeholder: 'Label'
                            })
                        );
                    })
                ),
                el('button', { onClick: addStat }, 'Add Stat')
            );
        },
        save: function () {
            return null;
        }
    });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components);
