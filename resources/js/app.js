require('./bootstrap');
require('tinymce');
require('tinymce/themes/silver');
require('tinymce/plugins/paste');
require('tinymce/plugins/advlist');
require('tinymce/plugins/autolink');
require('tinymce/plugins/lists');
require('tinymce/plugins/link');
require('tinymce/plugins/image');
require('tinymce/plugins/charmap');
require('tinymce/plugins/print');
require('tinymce/plugins/preview');
require('tinymce/plugins/anchor');
require('tinymce/plugins/textcolor');
require('tinymce/plugins/searchreplace');
require('tinymce/plugins/visualblocks');
require('tinymce/plugins/code');
require('tinymce/plugins/fullscreen');
require('tinymce/plugins/insertdatetime');
require('tinymce/plugins/media');
require('tinymce/plugins/table');
require('tinymce/plugins/contextmenu');
require('tinymce/plugins/code');
require('tinymce/plugins/help');
require('tinymce/plugins/wordcount');

$(document).ready(function () {
    tinymce.init({
        selector: '.tiny',
        height: 300,
        menubar: false,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor textcolor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table contextmenu paste code help wordcount'
        ],
        toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
        content_css: [
            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
            '//www.tinymce.com/css/codepen.min.css'
        ]
    });
});