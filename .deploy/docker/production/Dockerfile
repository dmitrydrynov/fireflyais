FROM webdevops/php-nginx:8.0

ARG NODE_VERSION=16

ENV WEB_DOCUMENT_ROOT=/app/public

RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | gpg --dearmor | tee /usr/share/keyrings/yarn.gpg >/dev/null \
    && echo "deb [signed-by=/usr/share/keyrings/yarn.gpg] https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list
RUN curl -sLS https://deb.nodesource.com/setup_$NODE_VERSION.x | bash -
RUN apt update && apt install -y iputils-ping nano cron nodejs yarn

WORKDIR /app
COPY --chown=application:application . .

COPY --chown=application:application ./storage storage
RUN chmod 755 -R storage

COPY --chown=application:application ./.deploy/docker/production/php.ini /etc/php/8.0/cli/conf.d/defaul-app.ini

ADD entrypoint.sh /app
RUN chmod +x /app/entrypoint.sh
HEALTHCHECK CMD bash /app/entrypoint.sh || exit 1