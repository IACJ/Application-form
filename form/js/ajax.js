 function submit(){  
        var name=document.getElementById('name').value;
        var sex=document.getElementById('sex').value;
        var college=document.getElementById('college').value;
        var grade=document.getElementById('grade').value;
        var dorm=document.getElementById('dorm').value;
        var phone=document.getElementById('phone').value;
        var department1=document.getElementById('department1').value;
        var department2=document.getElementById('department2').value;
        var intro=document.getElementById('intro').value;
        var token=document.getElementById('token').value;
        name = trim(name);
        dorm = trim(dorm);

        if(name==""||!isNaN(name)) {
            alert("Σ(⊙▽⊙| 请告诉梯仔你的三次元名字好吗");
            error("#name");
            return;
        }
        else if(sex=="") {
            alert("Σ(⊙▽⊙| 你是汉子还是妹纸呀？");
            error("#sex");
            return;
        }
        else if(college=="") {
            error("#college");
            alert("Σ(⊙▽⊙| 能告诉梯仔你来自哪个学院吗");
            return;
        }
        else if(grade=="") {
            error("#grade");
            alert("Σ(⊙▽⊙| 能告诉梯仔你是哪个年级的吗？");
            return;
        }
        else if(dorm=="") {
            error("#dorm");
            alert("Σ(⊙▽⊙| 能告诉梯仔你的宿舍号吗？");
            return;
        }
        else if(!jorm(dorm)){
            error("#dorm");
            alert("Σ(⊙▽⊙| 宿舍号的格式不对哦~");
            return;
        }
        else if(phone=="") {
            error("#phone");
            alert("Σ(⊙▽⊙| 能告诉梯仔你的手机号吗？");
            return;
        }
        else if(!jphone(phone)) {
            error("#phone");
            alert("∑( °△° |||这是手机号码咩");
            return;
        }
        else if(department1=="") {
            alert("Σ(⊙▽⊙| 能告诉梯仔你的第一志愿吗？");
            error1("#department1");
            return;
        }
        else if(department1==department2) {
            alert("Σ(⊙▽⊙| 两个志愿不要填的一样哦~");
            return;
        }
        else { 
            $.post("insert.php",
                {
                name:name,
                sex:sex,
                college:college,
                grade:grade,
                dorm:dorm,
                phone:phone,
                department1:department1,
                department2:department2,
                intro:intro,
                token:token
                },
                function(data,status) {
                    if (status=="success"){        
                        var condition = data;
                        condition = trim(condition);
                        switch (condition)
                        {
                            case "100": alert("报名已经截止QAQ下学期再来报名吧~");break;
                            case "99": alert("不好意思，还没有开放哦亲摸摸大~^_^");break;
                            case "0": alert("提交成功~请等待通知么么哒~\(≧▽≦)/~");break;
                            case "1": alert("信息不完整");break;
                            case "2": alert("信息错误QOQ");break;
                            case "3": alert("信息不能正常提交QUQ");break;
                            case "4": alert("已经提交过表单啦");break;
                            default:alert("未知错误");break;
                        }
                    }
                    else{                
                        alert("Request was unsuccessful: " + status);                       
                    }
                }
            );
            document.getElementById("btn").style.backgroundColor="grey";
            document.getElementById("btn").disabled=true;
            document.getElementById("btn").innerHTML="已提交";
        }
    }
    //检查姓名
    function jName(str){
        if(isNaN(str))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    //检查手机号
    function jphone(sphone){
        var p=/^1[3,4,5,7,8]\d{9}$/;
        if(p.test(sphone)){
            return true;
        }
        else {
            return false;
        }
    }
    //检查宿舍号
    function jorm(sdorm){
        var p = /^[Cc][01]?\d[ -]?[东西]?[ -]\d{3}$/;
        if(p.test(sdorm)){
            return true;
        }
        else {
            return false;
        }
    }
    //删除左右两端的空格
    function trim(str){ 
        return str.replace(/(^\s*)|(\s*$)/g, "");
    }

    function error(elem){
        $(elem).prev().css("color","#E41010");
        $(elem).parent().parent().css("border-color","#E41010");
    }

    function error1(elem){
        $(elem).parent().prev().css("color","#E41010");
        $(elem).parent().css("border-color","#E41010");
    }