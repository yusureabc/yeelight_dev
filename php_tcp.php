<?php 
/**
 * 采用php socket技术使用TCP/IP连接设备
 * @param string $service_port 连接端口
 * @param string $address      发送IP地址
 * @param string $in           发送命令
 * @return string/boolean 返回值
 */
function Send_socket_connect($service_port, $address, $in) {
  $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die('could not create socket!');
  $timeout = 2;
  //设置超时时间
  $time = time();
  //设置非阻塞模式
  socket_set_nonblock($socket);
  //超时判断
  while (!@socket_connect($socket, $address, $service_port)){
    $err = socket_last_error($socket);
    //连接成功，跳出循环
    if ($err === 10056) {
        break;
    } 
    //连接失败，判断超时时间，停止
    if ((time() - $time) >= $timeout) {
      socket_close($socket);
      return false;
      exit();
    } 
    //刷新频率（250毫秒）
    usleep(250000);
  }
  //设置阻塞模式
  socket_set_block($socket);
  //发送命令到设备
  socket_write($socket, $in, strlen($in));
  //接收设备命令返回数据
  $buffer = socket_read($socket, 1024, PHP_NORMAL_READ);
  //关闭连接
  socket_close($socket);
  //输出返回值
  return $buffer;
}

/* Toggle */
// $data = '{"id":1,"method":"toggle","params":[]}' . "\r\n";

/* Flash Notify( White ) */
// $data = '{"id":1,"method":"start_cf","params":["cf", 6, 0,"600,2,4000,70,400,2,4000,1"]}' . "\r\n";

/* Flash Notify( Red ) */
// $data = '{"id":1,"method":"start_cf","params":["cf", 6, 0,"600,1,16723245,70, 400,1,16723245,1"]}' . "\r\n";

/* Flash Notify( Green ) */
// $data = '{"id":1,"method":"start_cf","params":["cf", 6, 0,"600,1,9699131,70, 400,1,9699131,1"]}' . "\r\n";

/* Flash Notify( Blue ) */
$data = '{"id":1,"method":"start_cf","params":["cf", 6, 0,"600,1,7792639,70, 400,1,7792639,1"]}' . "\r\n"; 

/* Flash Notify( Yellow ) */
// $data = '{"id":1,"method":"start_cf","params":["cf", 6, 0,"600,1,16771675,70, 400,1,16771675,1"]}' . "\r\n";

/* Flash Notify( Purple ) */
// $data = '{"id":1,"method":"start_cf","params":["cf", 6, 0,"600,1,11903999,70, 400,1,11903999,1"]}' . "\r\n";

// $data = '{"id":1,"method":"set_name","params":["test_my_bulb"]}' . "\r\n";




$result = Send_socket_connect( '55443', '192.168.10.42', $data );
print_r( $result );