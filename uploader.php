<html>
<body>
<input type="file" id="selectFiles" value="Import" /><br />
<button id="import">Import</button>
<textarea id="result">
  
</textarea>

</body>
<script type="text/javascript">
  document.getElementById('import').onclick = function() {
  var files = document.getElementById('selectFiles').files;
  console.log(files);
  if (files.length <= 0) {
    return false;
  }
  
  var fr = new FileReader();
  
  fr.onload = function(e) { 
  console.log(e);
    var result = JSON.parse(e.target.result);
    var formatted = JSON.stringify(result, null, 2);
    document.getElementById('result').value = formatted;
  }
  
  fr.readAsText(files.item(0));
};
</script>
</html>
