(function (blocks, element, blockEditor) {
    var el = element.createElement;
    var useBlockProps = blockEditor.useBlockProps;

    blocks.registerBlockType('weoryx/team', {
        title: 'Team Section',
        icon: 'groups',
        category: 'weoryx',
        edit: function () {
            var blockProps = useBlockProps({
                style: { padding: '40px', background: '#fff', textAlign: 'center' }
            });
            return el('div', blockProps, 'Team Section Block');
        },
        save: function () { return null; }
    });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor);
