<script>
    $(function () {
        tinymce.init({
            selector:'textarea.tinymce',
            language: <?php echo json_encode(LANG[1] == 'es_ES' ? 'es' : LANG[1]) ?>,
            style_formats: [
                {
                    title: 'Headers', 
                    items: [
                        {title: 'Header 1', block: 'h1'},
                        {title: 'Header 2', block: 'h2'},
                        {title: 'Header 3', block: 'h3'},
                        {title: 'Header 4', block: 'h4'},
                        {title: 'Header 5', block: 'h5'},
                        {title: 'Header 6', block: 'h6'}
                    ]
                },
                {
                    title: 'Inline', 
                    items: [
                        {title: 'Bold', inline: 'b', icon: 'bold'},
                        {title: 'Italic', inline: 'i', icon: 'italic'},
                        {title: 'Underline', inline: 'span', styles : {textDecoration : 'underline'}, icon: 'underline'},
                        {title: 'Strikethrough', inline: 'span', styles : {textDecoration : 'line-through'}},
                        {title: 'Superscript', inline: 'sup', icon: 'superscript'},
                        {title: 'Subscript', inline: 'sub', icon: 'subscript'},
                        {title: 'Code', inline: 'code'}
                    ]
                },
                {
                    title: 'Blocks', 
                    items: [
                        {title: 'Paragraph', block: 'p'},
                        {title: 'Blockquote', block: 'blockquote'},
                        {title: 'Div', block: 'div'},
                        {title: 'Pre', block: 'pre'}
                    ]
                },
                {
                    title: 'Alinhamento', 
                    items: [
                        {title: 'Left', block: 'div', styles : {textAlign : 'left'}},
                        {title: 'Center', block: 'div', styles : {textAlign : 'center'}},
                        {title: 'Right', block: 'div', styles : {textAlign : 'right'}},
                        {title: 'Justify', block: 'div', styles : {textAlign : 'justify'}}
                    ]
                }
            ]
        });
    });
</script>