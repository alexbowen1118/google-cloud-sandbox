# Multi-stage
# 1) Node image for building frontend assets
# 2) nginx stage to serve frontend assets

# Name the node stage "builder"
FROM node:16 AS builder

# Set working directory
WORKDIR /app

# Copy just package.json
COPY package*.json .
# install node modules
RUN npm install
# Copy all files from current directory to working dir in image
COPY . .

# Configure the public url to facilitate functionality behind a reverse proxy
ENV PUBLIC_URL=/calendar

# install node modules and build assets
# Set the application's homepage
RUN npm run build

# nginx state for serving content
FROM nginx

# Set working directory to nginx asset directory
WORKDIR /var/www/html

# Remove default nginx static assets
RUN rm -rf ./*

# Copy static assets from builder stage
COPY --from=builder /app/build .
