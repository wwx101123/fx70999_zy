layui.use(['layer', 'form','laydate'], function(){
  var layer = layui.layer
  ,form = layui.form();
  $('.ajax-post').submit(function() {
    self=$(this);
    $.post(self.attr('action'),self.serialize(),
      function(data,status) {
        if (data.status) {
          layer.msg(data.info,{offset:'100px',time:1500,end:function() {
            if (data.url) {
              location.href=data.url;
            }else{
              location.reload();
            }

          }})
        }else{
          layer.msg(data.info,{offset:'100px'});
        }
      });
    return false;//阻止默认提交
  });
  $('.ajax-a').on('click',function() {
    var self=$(this);
    layer.confirm(self.attr('info'),{icon: 3, title:'警告'},function(index) {
      $url=self.attr('href');
      $.get($url,function(data) {
        if (data.status) {
          layer.msg(data.info,{time:1000,end:function() {
            if (data.url) {
              location.href=data.url;
            }else{
              location.reload();
            }
          }});
        }else{
          layer.msg(data.info);
        }
      });
    });
    return false;
  });
  $('.ajax-b').on('click',function() {

    var self=$(this);
    layer.confirm(self.attr('info'),{icon: 3, title:'警告'},function(index) {
      layer.msg('备份中,备份过程请勿有其他操作！',{icon:16,shade:0.01,time:50000});
      $url=self.attr('href');
      $.get($url,function(data) {
        if (data.status) {
          layer.msg("备份数据成功",{time:1000,end:function() {
            if (data.url) {
              setTimeout(function(){
                layer.closeAll('msg');
              }, 1000);
              location.href=data.url;
            }else{
              location.reload();
            }
          }});
        }else{
          layer.msg("备份数据成功");
          location.reload();
        }
      });
    });
    return false;
  });
  $('.ajax-h').on('click',function() {

    var self=$(this);
    layer.confirm(self.attr('info'),{icon: 3, title:'警告'},function(index) {
      layer.msg('还原中,还原过程请勿有其他操作！',{icon:16,shade:0.01,time:50000});
      $url=self.attr('href');
      $.get($url,function(data) {
        if (data.status) {
          layer.msg("数据还原成功！",{time:1000,end:function() {
            if (data.url) {
              setTimeout(function(){
                layer.closeAll('msg');
              }, 1000);
              location.href=data.url;
            }else{
              location.reload();
            }
          }});
        }else{
          layer.msg("数据还原成功！");
          location.reload();
        }
      });
    });
    return false;
  });
});
