FROM node:16


# Set working directory
WORKDIR /app

# Copy just package.json
COPY package.json .
# install node modules
RUN npm install
# Copy all files from current directory to working dir in image
COPY . .

# Configure the public url to facilitate functionality behind a reverse proxy
ENV PUBLIC_URL=/calendar
ENV PORT 80

ENTRYPOINT [ "npm", "start"]