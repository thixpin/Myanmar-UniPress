if (typeof Rabbit == 'undefined') {
    var head = document.head;
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = '//www.rabbit-converter.org/Rabbit/rabbit.js';
    head.appendChild(script);
}

(function() {

    tinymce.PluginManager.add( 'uni_to_zg', function( editor, url ) {
        // Add Button to Visual Editor Toolbar
        editor.addButton('uni_to_zg', {
            title: 'Convert to Zawgyi',
            cmd: 'uni_to_zg',
            image: url + '/../img/zawgyi.png',
        });
 
        editor.addCommand('uni_to_zg', function() {
            var selected_text = editor.selection.getContent({
                'format': 'html'
            });
            if ( selected_text.length === 0 ) {
                alert( 'Please select some text to convert.' );
                return;
            }

            var return_text = '';
            return_text = Rabbit.uni2zg(selected_text);
            editor.execCommand('mceReplaceContent', false, return_text);
            return;
        });
 
    });

    tinymce.PluginManager.add( 'zg_to_uni', function( editor, url ) {
        // Add Button to Visual Editor Toolbar
        editor.addButton('zg_to_uni', {
            title: 'Convert to Unicode',
            cmd: 'zg_to_uni',
            image: url + '/../img/unicode.png',
        });
 
        editor.addCommand('zg_to_uni', function() {
            var selected_text = editor.selection.getContent({
                'format': 'html'
            });
            if ( selected_text.length === 0 ) {
                alert( 'Please select some text to convert.' );
                return;
            }

            var return_text = '';
            return_text = Rabbit.zg2uni(selected_text);
            editor.execCommand('mceReplaceContent', false, return_text);
            return;
        });
 
    });
})();