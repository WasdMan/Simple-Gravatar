<?php

global $helptxt;

$helptxt['gravatar_title'] = 'Here can set up use of avatars of users from a site <a href="http://gravatar.com/" target="_blank">Gravatar</a>';

$helptxt['gravatar_enable'] = 'Function includes/disconnects operation a module <strong>Simple Gravatar</strong>.';

$helptxt['gravatar_default_face'] = '<strong>404:</strong> do not load any image if none is associated with the email hash, instead return an HTTP 404 (File Not Found) response<br />
<strong>mm:</strong> (mystery-man) a simple, cartoon-style silhouetted outline of a person (does not vary by email hash)<br />
<strong>identicon:</strong> a geometric pattern based on an email hash<br />
<strong>monsterid:</strong> a generated \'monster\' with different colors, faces, etc<br />
<strong>wavatar:</strong> generated faces with differing features and backgrounds<br />
<strong>retro:</strong> awesome generated, 8-bit arcade-style pixelated faces';

$helptxt['gravatar_rating'] = '<strong>g:</strong> suitable for display on all websites with any audience type.<br />
<strong>pg:</strong> may contain rude gestures, provocatively dressed individuals, the lesser swear words, or mild violence.<br />
<strong>r:</strong> may contain such things as harsh profanity, intense violence, nudity, or hard drug use.<br />
<strong>x:</strong> may contain hardcore sexual imagery or extremely disturbing violence.';

$helptxt['gravatar_transfer_protocol'] = 'If you\'re displaying Gravatars on a page that is being served over SSL (e.g. the page URL starts with HTTPS), then you\'ll want to serve your Gravatars via SSL as well, otherwise you\'ll get annoying security warnings in most browsers.';

$txt['permissionhelp_profile_gravatar_avatar'] = 'This permission allows the user to use avatars from site gravatar.com.';