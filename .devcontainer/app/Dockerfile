FROM mcr.microsoft.com/devcontainers/php:0-8.2

# Install MariaDB client
RUN apt-get update && export DEBIAN_FRONTEND=noninteractive \
    && apt-get install -y mariadb-client \ 
    && apt-get clean -y && rm -rf /var/lib/apt/lists/*

# Install php-mysql driver
RUN docker-php-ext-install mysqli pdo pdo_mysql

# [Optional] Uncomment this line to install global node packages.
# RUN su vscode -c "source /usr/local/share/nvm/nvm.sh && npm install -g <your-package-here>" 2>&1

# Setup the dev workspace to be hosted
RUN mkdir /var/www/local_dev
RUN ln -sT /workspaces/PersonalWebsite/ /var/www/local_dev/public_html

RUN echo "<VirtualHost *:80>" > /etc/apache2/sites-available/000-default.conf
RUN echo "  DocumentRoot /var/www/local_dev/public_html" >> /etc/apache2/sites-available/000-default.conf

# Put the error logs somewhere we can see them
# RUN echo "  ErrorLog /local_dev_error.log" >> /etc/apache2/sites-available/000-default.conf
# RUN touch /local_dev_error.log
# RUN chown www-data:www-data /local_dev_error.log
# RUN chmod 777 /local_dev_error.log

RUN echo "</VirtualHost>" >> /etc/apache2/sites-available/000-default.conf

# Give apache permission to see the files
RUN chown www-data:www-data /var/www/local_dev
RUN chown -h www-data:www-data /var/www/local_dev/public_html
RUN usermod -a -G vscode www-data

# Enable the rewrite mod so our .htaccess is valid
RUN ln -s /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/

# Remove the apache Alias mod (it hides the 'icons' directory so the sidebar icons can't be found)
RUN rm /etc/apache2/mods-enabled/alias.*

# Copy the credentials file into the required location
COPY example_credentials.ini /var/www/local_dev/private/database_credentials.ini

# Start apache automatically when the container starts
# ENTRYPOINT "/bin/bash apachectl start"
