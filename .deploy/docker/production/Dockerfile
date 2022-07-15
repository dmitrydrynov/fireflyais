FROM webdevops/php-nginx:8.0

ARG NODE_VERSION=16

ENV WEB_DOCUMENT_ROOT=/app/public

RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | gpg --dearmor | tee /usr/share/keyrings/yarn.gpg >/dev/null \
    && echo "deb [signed-by=/usr/share/keyrings/yarn.gpg] https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list
RUN curl -sLS https://deb.nodesource.com/setup_$NODE_VERSION.x | bash -
RUN apt update && apt install -y iputils-ping nano cron nodejs yarn

WORKDIR /app
COPY --chown=application:application . .

ADD entrypoint.sh /app
RUN chmod +x /app/entrypoint.sh
HEALTHCHECK CMD bash /app/entrypoint.sh > /dev/null 2>&1 && echo "Healthy"  || exit 1