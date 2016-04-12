<pre>
     _                 _                         _
 ___(_)_ __ ___  _ __ | | ___  _ __     __ _ ___| |_ ___  _ __ __ _  __ _  ___
/ __| | '_ ` _ \| '_ \| |/ _ \| '_ \   / _` / __| __/ _ \| '__/ _` |/ _` |/ _ \
\__ \ | | | | | | |_) | | (_) | | | | | (_| \__ \ || (_) | | | (_| | (_| |  __/
|___/_|_| |_| |_| .__/|_|\___/|_| |_|  \__, |___/\__\___/|_|  \__,_|\__, |\___|
                |_|                    |___/                        |___/
</pre>

# Requirements

You need `Server account credentials` as `JSON` which you can generate within your `Google Console`.
You will receive a file which looks like the following example:

```json
{
  "type": "service_account",
  "project_id": "foo.bar:api-project-XXXXXXXXXXXXXX",
  "private_key_id": "XXXXXXXXXXXXXX",
  "private_key": "-----BEGIN PRIVATE KEY-----\nXXXXXXXXXXXXXX\n-----END PRIVATE KEY-----\n",
  "client_email": "cloud-storage-web-server@api-project-XXXXXXXXXXXXXX.foo.bar.iam.gserviceaccount.com",
  "client_id": "XXXXXXXXXXXXXX",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://accounts.google.com/o/oauth2/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/cloud-storage-web-server%40api-project-XXXXXXXXXXXXXX.foo.bar.iam.gserviceaccount.com"
}
```

# Setup credentials

Gstorage needs your credentials in order to be constructed. For this we need to use the `ServerAccountCredentials` which offers two methods to load our credentials.

## Load from params

Use `client_email` and `private_key` from your JSON file above for this method.

```php
$credentials = (new ServerAccountCredentials())->loadFromParams(
    "cloud-storage-web-server@api-project-XXXXXXXXXXXXXX.foo.bar.iam.gserviceaccount.com",
    "-----BEGIN PRIVATE KEY-----\nXXXXXXXXXXXXXX\n-----END PRIVATE KEY-----\n"
);
```

## Load from JSON file

Specify the file path for your JSON file. Lets assume we saved our file within the same folder and named it `credentials.json`.

```php
$credentials = (new ServerAccountCredentials())->loadFromJsonFile('credentials.json');
```

# Instantiate Gstorage class

Now all what needs to be done is to use `$credentials`:

```php
$gstorage = new GoogleStorage($credentials);
```

# Upload a file

Lets upload a file. We will construct an upload object and receive a simple object back.

```php
// upload object
$data = new UploadData('YOUR-BUCKET-NAME');

// load file via URL
$data->loadWithFile('https://www.google.com/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png');

// upload file
$objectData = $gstorage->upload($data); // ObjectData|null

if ($objectData)
{
    var_dump([
        'bucket'     => $objectData->getBucket(),
        'id'         => $objectData->getFileName(),
        'url_public' => $objectData->getUrlPublic(),
    ]);
}
```

## Upload via BLOB

In case you have the file data already you can upload your data as following:

```php
// our blob
$blob = file_get_contents('https://www.google.com/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png');

// upload object
$data = new UploadData('YOUR-BUCKET-NAME');

// load file via BLOB
$data->loadWithBlob('google-logo.png', $blob);

// upload file
$objectData = $gstorage->upload($data); // ObjectData|null

if ($objectData)
{
    var_dump([
        'bucket'     => $objectData->getBucket(),
        'id'         => $objectData->getFileName(),
        'url_public' => $objectData->getUrlPublic(),
    ]);
}
```

# Delete file

Lets delete the upload from our `Google Logo Blob`:

```php
$response = $gstorage->delete(
    new ObjectData('YOUR-BUCKET-NAME', 'google-logo.png')
);

var_dump($response); // true|false
```

# Complete example

```php
$gstorage = new GoogleStorage(
    (new ServerAccountCredentials())->loadFromJsonFile('credentials.json')
);

// upload object
$data = new UploadData('YOUR-BUCKET-NAME');

// load file via URL
$data->loadWithFile('https://www.google.com/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png');

// upload file
$objectData = $gstorage->upload($data); // ObjectData|null

if ($objectData)
{
    var_dump([
        'bucket'     => $objectData->getBucket(),
        'id'         => $objectData->getFileName(),
        'url_public' => $objectData->getUrlPublic(),
    ]);
}
```

# License
Cirrus is freely distributable under the terms of the MIT license.

Copyright (c) 2016 Tino Ehrich ([tino@bigpun.me](mailto:tino@bigpun.me))

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.