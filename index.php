<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Html 5 Notes App</title>
		<script type="text/javascript" src="jquery-1.6.2.min.js"></script>
		<style type="text/css">
			#wrap {
				display: block;
			}
			#noteform {
				width: 20%;
				float: left;
				background: #e5e5e5;
			}
			#visiblenotes {
				width: 70%;
				float: right;
			}
		</style>
		<script type="text/javascript">
		var dta_b={
					dbase_name:"notes",
					version:"1.0",
					description:"a notes database",
					size: "1024*1024*3",
					openDB:function(){
						return window.openDatabase(this.dbase_name,this.version,this.description,this.size);
					}
				};
			var db=dta_b.openDB();
			
			db.transaction(function(tx) {
				var query = "SELECT id,title,note FROM notestaken";
				tx.executeSql(query, [], function(SQLTransaction, data) {
					var title;
					var note;
					for(var i = 0; i < data.rows.length; ++i) {
						var row = data.rows.item(i);
						title = row['title'];
						note = row['note'];

						var dsp = "<li>" + title + "<p/>" + note + "</li>";
						$("#visiblenotes").append(dsp);
					}
				}, function() {
					window.alert("Something bad happened displaying");
				});
			});
			db.transaction(function(tx) {
				tx.executeSql("CREATE TABLE notestaken (id INTEGER PRIMARY KEY, title TEXT, note TEXT)", [], function() {
					//window.alert("table created");
				}, function() {
					//window.alert("Table not created");
				});
			});
			$(function() {
				
				
				$("#save").click(function() {
					var title = $("#title").val();
					var note = $("#note").val();
					
						db.transaction(function(tx) {
						var query = "INSERT INTO notestaken(title,note) VALUES(?,?)";
						tx.executeSql(query, [title, note], function(tx, result) {
							//window.alert("note inserted");
							db.transaction(function(tx) {
								var query = "SELECT id,title,note FROM notestaken";
								tx.executeSql(query, [], function(SQLTransaction, data) {
									var title;
									var note;
									for(var i = 0; i < data.rows.length; ++i) {
										var row = data.rows.item(i);
										title = row['title'];
										note = row['note'];

										var dsp = "<li>" + title + "<p/>" + note + "</li>";
										$("#visiblenotes").append(dsp);
									}
								}, function() {
									window.alert("Something bad happened displaying");
								});
							});
						}, function() {
							window.alert("note not inserted,please check your code");
						});
					});
				});
				
				$("#deleteall").click(function(){
					var db=dta_b.openDB();	
					db.transaction(function(tx){
						var delete_query="DELETE FROM notestaken";
						tx.executeSql(delete_query,[],function(){
							window.alert("all the records deleted");
						},function(){
							window.alert("records not deleted");
						})
					});
					
				});
			});

		</script>
	</head>
	<body>
		<div id="wrap">
			<div id="noteform">
				<form>
					<label for="title">Title</label>
					<input type="text" id="title"/>
					<p/>
					<label for="note">Note</label>
					<textarea id="note"></textarea>
					<p/>
					<input type="button" value="Save" id="save"/>
				</form>
			</div>
			<div id="visiblenotes">
				<input type="button" id="deleteall" value="Delete All"/>
			</div>
		</div>
	</body>
</html>
