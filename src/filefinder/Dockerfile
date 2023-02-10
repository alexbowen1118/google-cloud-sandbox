FROM node:16

# Set working directory
WORKDIR /app


# Copy all files from current directory to working dir in image
COPY package.json .

# install node modules and build assets
RUN npm install

COPY . .

ENV PUBLIC_URL=/filefinder
ENV PORT 80 
ENV CHOKIDAR_USEPOLLING=true
ENV REACT_APP_CHOKIDAR_USEPOLLING=true 


ENTRYPOINT [ "npm", "start"]


# # Prod setting commented out
# # Multi-stage
# # 1) Node image for building frontend assets
# # 2) nginx stage to serve frontend assets

# # Name the node stage "builder"
# FROM node:18.9 AS builder

# ARG PUBLIC_URL

# # Set working directory
# WORKDIR /app

# # Copy all files from current directory to working dir in image
# COPY . .

# # Configure the public url to facilitate functionality behind a reverse proxy
# ENV PUBLIC_URL=/filefinder

# # install node modules and build assets
# # Set the application's homepage 
# RUN npm install && npm run build

# # nginx state for serving content
# FROM nginx

# # Set working directory to nginx asset directory
# WORKDIR /var/www/html/filefinder

# # Remove default nginx static assets
# RUN rm -rf ./*

# # Copy static assets from builder stage
# COPY --from=builder /app/build .
