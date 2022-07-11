FROM nginx:1.12.2

COPY ./default.conf /etc/nginx/conf.d/default.conf

ENV TZ=Europe/Kiev
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN mkdir -p /var/run/
