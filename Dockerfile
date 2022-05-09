FROM webdevops/php-nginx:8.0

RUN apt update && apt install -y nano

WORKDIR /app
COPY --chown=application:application . .

RUN composer install -d /app
#RUN composer create-project grumpydictator/firefly-iii --no-dev --prefer-dist firefly-iii 5.7.2

## Fixes
# Disable hardcoded CSP headers
#          = config('firefly.disable_csp_header');
# =true;
RUN sed -i '/$disableCSP/a $disableCSP=true;' /app/app/Http/Middleware/SecureHeaders.php
# Mixed Content: The page at '<URL>' was loaded over HTTPS, but requested an insecure stylesheet '<URL>'. This request has been blocked; the content must be served over HTTPS.
# ['HTTPS'] = 'on';
RUN sed -i "2i\$_SERVER['HTTPS'] = 'on';" /app/public/index.php

ADD finalize-image.sh /app
RUN chmod +x /app/finalize-image.sh
HEALTHCHECK CMD bash /app/finalize-image.sh || exit 1

