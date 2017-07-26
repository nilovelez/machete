<?php $machete_codemirror_url = MACHETE_BASE_URL.'vendor/codemirror/'; ?>


  <link rel="stylesheet" href="<?php echo $machete_codemirror_url ?>lib/codemirror.css">
  <link rel="stylesheet" href="<?php echo $machete_codemirror_url ?>theme/monokai.css">
  <script src="<?php echo $machete_codemirror_url ?>lib/codemirror.js"></script>
  <script src="<?php echo $machete_codemirror_url ?>mode/xml.js"></script>
  <script src="<?php echo $machete_codemirror_url ?>mode/javascript.js"></script>
  <script src="<?php echo $machete_codemirror_url ?>mode/css.js"></script>
  <script src="<?php echo $machete_codemirror_url ?>mode/htmlmixed.js"></script>
  <script src="<?php echo $machete_codemirror_url ?>addon/selection/active-line.js"></script>
  <script src="<?php echo $machete_codemirror_url ?>addon/edit/matchbrackets.js"></script>
  <script src="<?php echo $machete_codemirror_url ?>addon/edit/closetag.js"></script>
  <script src="<?php echo $machete_codemirror_url ?>addon/edit/closebrackets.js"></script>
 
<style>
.CodeMirror {border: 1px solid #999; font-size:13px; max-width: 820px;}
</style>
<script>
(function($){
  $(function() {
    var CodeMirror_options = {
      lineNumbers: true,
      styleActiveLine: true,
      matchBrackets: true,
      mode: "text/html",
      matchBrackets: true,
      autoCloseTags: true,
      autoCloseBrackets: true,
      //theme: 'monokai'
    };
    var editors = {
      header_editor : CodeMirror.fromTextArea(document.getElementById("header_content"), CodeMirror_options),
      alfonso_editor : CodeMirror.fromTextArea(document.getElementById("alfonso_content"), CodeMirror_options),
      footer_editor : CodeMirror.fromTextArea(document.getElementById("footer_content"), CodeMirror_options)
    }

  });
})(jQuery);
</script>