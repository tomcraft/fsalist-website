FROM nginx:1.17.5-alpine

# Copy nginx config
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Copy assets
COPY public /app/public
