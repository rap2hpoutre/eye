
<h3>Hi there,</h3>
<p>This is a support page, where the below is all the configuration options for your application. We have attempted to automatically remove known senstive data from the config. This includes any "key", "password" or "secret" config item, as well as all "services".</p>
<p><strong>We highly recommend you check the config below for any other possible secret values before emailing us a copy.</strong></p>
<p>If there are more secrets, please remove them prior to emailing. Once you are happy - please "copy and paste" all the data below, and email it to <strong>support@eyewitness.io</strong> - and we'll look into the issue further.</p>

<hr/>

<pre>
{{ var_dump($eye->application()->settings()) }}
</pre>

<pre>
{{ var_dump($config) }}
</pre>

