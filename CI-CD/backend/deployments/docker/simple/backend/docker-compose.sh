docker build -f ./dockerfile -t chat-backend-image ../../../../
docker run -d -p 4000:4000 -v /Users/bilalafzal/Documents/projects/CI-CD/backend:/usr/src/app --name chat-backend-container --network chat-network chat-backend-image
