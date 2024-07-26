# httpd base image
FROM httpd:2.4

ARG WWWGROUP

# Create 'raft' group and user
RUN groupadd --force -g ${WWWGROUP} raft && \
    useradd -ms /bin/bash --no-user-group -g ${WWWGROUP} -u 1000 raft

# Update and install necessary packages
RUN apt-get update && apt-get install -y \
    openssl \
    libnghttp2-dev \
    && rm -rf /var/lib/apt/lists/*


# Change the Apache user and group
RUN sed -i \
-e 's/^User www-data/User raft/' \
-e 's/^Group www-data/Group raft/' \
/usr/local/apache2/conf/httpd.conf

# Copy the Apache virtual host configuration file to the container
# COPY ./docker/apache/conf/extra /usr/local/apache2/conf/extra

# Enable necessary Apache modules
RUN sed -i \
-e 's/^#\(Include .*httpd-vhosts.conf\)/\1/' \
-e '/#LoadModule socache_shmcb_module/s/^#//g' \
-e '/#LoadModule deflate_module/s/^#//g' \
-e '/#LoadModule proxy_module/s/^#//g' \
-e '/#LoadModule proxy_fcgi_module/s/^#//g' \
-e '/#LoadModule rewrite_module/s/^#//g' \
-e '/#LoadModule headers_module/s/^#//g' \
/usr/local/apache2/conf/httpd.conf