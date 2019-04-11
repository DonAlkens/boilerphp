# BoilerPHP
Mini Php framework for creating web application

<h2>Steps in Getting Started with Boiler Framework</h2>
<strong>Make sure the latest version of php install on your computer</strong>
<ul><li>Clone the boiler framework</li></ul>


<h4>Configure your host</h4>
<span>Open your the <span style="color:dodgerblue;">hosts</span> file in your <span style="color:dodgerblue;">C:/Windows/System32/drivers/etc/</span> folder, add your app host like described below.</span> <br><br>
<small><b>127.0.0.1 smsbreeze.com</b></small><br>

<h4>Create a Virtual host</h4>
<span>Navigate to your <span style="color:dodgerblue;">httpd-vhosts.conf</span> file in your <span style="color:dodgerblue;">C:/xampp/apache/conf/extra/</span> folder and create a virtual like below.</span> <br><br>

<small>
&lt;VirtualHost *:80&gt; <br>
    &nbsp;&nbsp;&nbsp;DocumentRoot "C:/projectpath/myproject" <br>
    &nbsp;&nbsp;&nbsp;ServerName smsbreeze.com <br>
&lt;/VirtualHost&gt;
</small>

