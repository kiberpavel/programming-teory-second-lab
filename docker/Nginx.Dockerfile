FROM nginx

ADD . /var/www/app
ADD docker/conf/default /etc/nginx/sites-enabled/
ADD docker/conf/nginx-main.conf /etc/nginx/nginx.conf


WORKDIR /var/www/app

EXPOSE 90

ENTRYPOINT ["nginx", "-g", "daemon off;"]
