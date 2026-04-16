(function (blocks, element, blockEditor) {
    var el = element.createElement;
    var useBlockProps = blockEditor.useBlockProps;

    blocks.registerBlockType('weoryx/blog', {
        title: 'Blog Section',
        icon: 'admin-post',
        category: 'weoryx',
        edit: function () {
            var blockProps = useBlockProps({
                style: { padding: '20px', border: '1px dashed #ccc', textAlign: 'center' }
            });
            return el('div', blockProps, 'Blog Grid Block');
        },
        save: function () { return null; }
    });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor);
