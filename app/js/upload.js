var ServerIP = "";
var IP = localStorage.getItem('IP');
var PreUrl = localStorage.getItem('PreUrl');
var UploadUrl = localStorage.getItem('UploadUrl');
var IndexUrl = localStorage.getItem('IndexUrl');
/**
 * 获取商品列表   
 */
var Upload_func = PreUrl + "Upload/Upload";

var files = [];

function init_upload(obj, value_obj, txt, upload_user_id) {

	localStorage.setItem('upload_obj_id', value_obj);
	localStorage.setItem('upload_user_id', upload_user_id);

	openWindowWithTitle(txt, 'upload.html')
	//	var btnArray = [{
	//		title: "拍照"
	//	}, {
	//		title: "从相册选择"
	//	}];
	//	var a = plus.nativeUI.actionSheet({
	//		title: "选择照片",
	//		cancel: "取消",
	//		buttons: btnArray
	//	}, function(e) {
	//		var index = e.index;
	//		switch(index) {
	//			case 0:
	//				break;
	//			case 1:
	//				var cmr = plus.camera.getCamera();
	//		
	//				cmr.captureImage(function(path) {
	//					console.log(cmr)
	//					files = [];
	//					files.push({
	//						name: "uploadkey" + index,
	//						path: path
	//					});
	//					upload(files, obj, value_obj, back);
	//
	//				}, function(err) {
	//
	//					console.log(err)
	//				});
	//				break;
	//			case 2:
	//				plus.gallery.pick(function(path) { 
	//						files = [];
	//						files.push({
	//							name: "uploadkey" + index,
	//							path: path
	//						});
	//						upload(files, obj, value_obj, back);
	//
	//					},
	//					function(err) {
	//
	//						console.log(err)
	//					}, null);
	//				break;
	//		}
	//	});
}

function upload(files, obj, value_obj, back) {
	if(files.length <= 0) {
		plus.nativeUI.alert("没有添加上传文件！");
		return;
	}
	var wt = plus.nativeUI.showWaiting();
	var task = plus.uploader.createUpload(Upload_func, {
			method: "POST"
		},
		function(t, status) {

			console.log(JSON.stringify(t))
			if(status == 200) {

				var value = JSON.parse(t.responseText);
				if(value) {
					mui.toast(value.msg)
					console.log(value.url)
					$(obj).attr('src', UploadUrl + value.url)
					localStorage.setItem('upload_user_id', 0);
					//					$(value_obj).val(value.url)
					//					obj.attr('src', UploadUrl + value.url)
					//					console.log(obj)
					$(value_obj).val(value.url)

					back && back(obj, value);
				} else {

					console.log(2222)
				}
				wt.close();

			} else {
				wt.close();
			}
		}
	);
	console.log(localStorage.getItem('upload_user_id'))
	task.addData("user_id", localStorage.getItem('upload_user_id'));
	task.addData("client", "HelloH5+");
	//	console.log(files.length)
	for(var i = 0; i < files.length; i++) {
		var f = files[i];
		task.addFile(f.path, {
			key: f.name
		});
	}
	task.start();
}

var files = [];
//选择图片
function selectImage(obj, value_obj) {

	plus.gallery.pick(function(paths) {
		console.log(paths)
		files.push({
			name: "uploadkey",
			path: paths
		});

		loadImage(files, obj, value_obj)

	});
}
//选择视频
function selectVideo(obj, value_obj) {

	plus.gallery.pick(function(paths) {
		console.log(paths)
		files.push({
			name: "uploadkey",
			path: paths
		});

		loadVideo(files, obj, value_obj)
	}, function(e) {
		mui.toast("没有选择视频.");
	}, {
		filter: "video"
	});
}

function captureVideo(obj, value_obj) {   
	var cmr = plus.camera.getCamera();   
	cmr.startVideoCapture(function(p) {      
		plus.io.resolveLocalFileSystemURL(p, function(entry) {         
			entry.file(function(file) {        
				var fileReader = new plus.io.FileReader();        

				files.push({
					name: "uploadkey",
					path: p
				});

				loadVideo(files, obj, value_obj);

				      
			});      
		}, function(e) {         
			alert('读取录像文件错误：' + e.message);      
		});   
	}, function(e) {   }, {
		filename: '_doc/camera/',
		index: 1
	});
}

function showVideo(file) {   
	$("#video0").remove(); //每次展示视频前先删除上一次生成的video
	   
	var fileSize = (file.size) / (1024 * 1024); //转换成M
	    
	fileSize = fileSize.toFixed(1); //保留小数点后一位
	    
	if(fileSize > 30) {       
		alert('上传视频不能大于30M');       
	} else {           
		var vde = '<video style="height:80px; width:100px; object-fit:fill" id="video0" autoplay="autoplay" x5-playsinline="" playsinline="" webkit-playsinline="" loop="loop"></video>';                      
		$(".photo").append(vde);            
		var reader = new plus.io.FileReader();      
		reader.readAsDataURL(file); //调用自带方法进行转换  
		      
		reader.onload = function(e) {           
			$("#video0").attr("src", e.target.result); //将视频base64编码放入标签
			      
		};          
	}      
}
//拍照 
function captureImage(obj, value_obj) {
	var cmr = plus.camera.getCamera(2);
	cmr.captureImage(
		function(paths) {
			//将图片地址转换
			console.log(paths)
			files.push({
				name: "uploadkey",
				path: paths
			});

			loadImage(files, obj, value_obj);

		},
		function(error) {
			mui.toast(error.message);
		}, {
			filename: "_documents/"
		}
	);

}

//确定选择图片
function loadImage(file, obj, value_obj) {
	//	var img = document.getElementById("userImage_id");
	//	img.src = path;
	//	if(window.imageDisable == true) {
	//		$("#userImage_id").cropper("enable");
	//	}
	//	$("#userImage_id").cropper("replace", path);
	//	//启用几个功能按钮
	//	$("button.toolbutton").prop("disabled", false);
	//	document.getElementById("userImage_id").onclick = function() {
	//		plus.runtime.openFile(path);
	//	}
	upload(file, obj, value_obj, img_back)

}
//确定选择图片
function loadVideo(file, obj, value_obj) {
	//	var img = document.getElementById("userImage_id");
	//	img.src = path;
	//	if(window.imageDisable == true) {
	//		$("#userImage_id").cropper("enable");
	//	}
	//	$("#userImage_id").cropper("replace", path);
	//	//启用几个功能按钮
	//	$("button.toolbutton").prop("disabled", false);
	//	document.getElementById("userImage_id").onclick = function() {
	//		plus.runtime.openFile(path);
	//	}
	upload(file, obj, value_obj, video_back)

}

function img_back(obj, data) {

	$(obj).attr('src', IP + '/' + data.url)
}

function video_back(obj, data) {

	$(obj).attr('src', '../images/play_icon80.png')

} 

function set_upload(obj, upload_user_id) {
	localStorage.setItem('upload_user_id', upload_user_id);

	var value_obj = $(obj).next();
	var editButtons = new Array();
	editButtons.push({
		title: "拍照上传",
		style: "default"
	});
	editButtons.push({
		title: "从相册选择",
		style: "default"
	});
	plus.nativeUI.actionSheet({
		cancel: "取消",
		buttons: editButtons
	}, function(e) {
		var index = e.index;
		switch(index) {
			case 1:
				captureImage(obj, value_obj); //拍照
				break;
			case 2:
				selectImage(obj, value_obj); //相册选择
				break;
		}
	});

}