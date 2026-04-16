(function (blocks, element, blockEditor) {
    var el = element.createElement;
    var useBlockProps = blockEditor.useBlockProps;

    blocks.registerBlockType('weoryx/mission-vision', {
        title: 'Mission & Vision',
        icon: 'visibility',
        category: 'weoryx',
        edit: function () {
            var blockProps = useBlockProps({
                style: { padding: '40px', background: '#fff', textAlign: 'center', border: '1px solid #eee' }
            });
            return el('div', blockProps, 'Mission & Vision Section');
        },
        save: function () { return null; }
    });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor);
