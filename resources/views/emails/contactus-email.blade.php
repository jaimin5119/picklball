<!DOCTYPE html>
<html>
    <head>
        <title>Contact enquiry</title>
    </head>
    <body>
       
    <center>
    <h2 style="padding: 23px;background: #b3deb8a1;border-bottom: 6px green solid;">
        <a href="#">Visit Our Website :</a>
    </h2>
    </center>
    
    <p><b>Hello,Admin</b></p>
    <p>Name:- {{$name}}</p>
    <p>Email:- {{$email}}</p>
    <p>Mobile:- {{$mobile}}</p>
    <p>Subject:- {{$subject}}</p>
    <br>
    {!!$message!!}
    <br>
   <strong>Thank you!</strong>
      
    </body>
</html>