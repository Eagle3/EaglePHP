#!/bin/sh
 
# Linux环境SVN服务脚本
# cp svn /etc/init.d/svn   拷贝脚本到 /etc/init.d/目录
# chkconfig --add svn      (首先，添加为系统服务,注意add前面有两个横杠)、
# chkconfig --level 2345 （启动级别）
# chkconfig svn on  （开机自启动）
# chkconfig --list （列表显示）
# service svn start（启动服务，就是执行svn的脚本）

# chkconfig:    2345 92 30 
# description:  svn server
#   

svnrep=/usr/local/nginx/html/svnrep
svnserve=/usr/bin/svnserve

#start
start(){
        PID=`ps -ef | grep 'svnserve' | grep -v 'grep' | awk '{print $2}'`
        if [ ${PID} ] ;then
                echo "svnserver is already running....."
                return 5
        else
                `"${svnserve}" -d -r "${svnrep}"`
                echo "svnserver is started"
                return 0
        fi
}
#stop
stop(){
        PID=`ps -ef | grep 'svnserve' | grep -v 'grep' | awk '{print $2}'`
        if [ ${PID} ] ; then
                `kill -9 ${PID}`
                echo "svnserver is stoped "
                return 0
        else
                echo "svnserver is not run "
                return 5
        fi
 
}
#restart
restart(){
        stop
        start
}
usage(){
        echo "Usage:{start|stop|restart}"
}

case $1 in
start)
        start
        ;;
stop)
        stop
        ;;
restart)
        restart
        ;;
*)
        usage
        exit 7
        ;;
esac