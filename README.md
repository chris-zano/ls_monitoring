# **LS Monitoring - Comprehensive Documentation**

## **Overview**
LS Monitoring is a **containerized application** that monitors and logs system metrics using **PHP, Nginx, MySQL**, and **AWS CloudWatch** for centralized logging. This project was designed to run on **AWS EC2** with a **Dockerized setup**, ensuring scalability, maintainability, and real-time system monitoring.

## **Key Learnings**
Through the development and deployment of LS Monitoring, the following key technical insights were gained:
1. **Containerized Application Deployment**: Using Docker and Docker Compose for service orchestration.
2. **Logging Infrastructure with AWS CloudWatch**: Setting up centralized logs from containers to CloudWatch.
3. **Database Persistence in Containers**: Ensuring MySQL retains data beyond container lifecycle.
4. **EC2 vs ECS Deployment Strategies**: Understanding the differences in performance, scalability, and automation.
5. **Troubleshooting High CPU Utilization in AWS**: Diagnosing performance issues in cloud environments.
6. **Nginx as a Reverse Proxy for PHP**: Configuring Nginx to serve PHP applications efficiently.

## **Tech Stack**
- **Backend**: PHP (Standalone Server)
- **Web Server**: Nginx
- **Database**: MySQL (Persisted using Docker Volumes)
- **Containerization**: Docker & Docker Compose
- **Cloud Infrastructure**: AWS ECS (Elastic Container Service)
- **Logging**: AWS CloudWatch Logs
- **Monitoring**: AWS CloudWatch Metrics
- **Networking**: AWS VPC, Security Groups

## **System Architecture**
The system follows a **microservices-like architecture**, where:
- **Nginx** acts as a **reverse proxy** and routes requests to PHP.
- **PHP** processes requests, interacts with MySQL, and serves dynamic content.
- **MySQL** provides persistent storage for system logs and metrics.
- **CloudWatch** collects logs from containers and provides real-time insights.

### **Architecture Diagram (Simplified)**
```
 User --> Nginx (Proxy) --> PHP Application --> MySQL Database
                            |
                          Logs
                            v
                      AWS CloudWatch
```

## **Environment Setup & Configuration**

### **Prerequisites**
- AWS Account with IAM permissions
- Docker & Docker Compose installed
- AWS CLI configured

### **Local Development Setup**
1. **Clone the repository**
   ```sh
   git clone https://github.com/chris-zano/ls_monitoring.git
   cd ls_monitoring
   ```
2. **Build and run the Docker containers**
   ```sh
   docker-compose up --build
   ```
3. **Access the application** via browser:
   ```
   http://localhost
   ```

### **Deploying to AWS ECS**
1. **Authenticate with AWS**
   ```sh
   aws configure
   ```
2. **Push Docker images to Amazon ECR**
   ```sh
   docker tag php_app:latest <AWS_ECR_REPO_URL>:latest
   docker push <AWS_ECR_REPO_URL>:latest
   ```
3. **Create ECS Cluster & Deploy Services**
   - Define ECS Task Definitions
   - Configure Load Balancer & Auto Scaling
   - Set up IAM Roles for CloudWatch logging

## **Application Features**
### **1. Dynamic System Monitoring Dashboard**
- Displays real-time system metrics.
- Uses in-element CSS for styling.

### **2. Persistent Database Storage**
- MySQL stores log data persistently.
- Ensures no data loss when restarting containers.

### **3. AWS CloudWatch Integration**
- Captures **Nginx, PHP, and MySQL logs**.
- Provides **real-time monitoring** via AWS CloudWatch dashboards.

### **4. Error Handling & Logging**
- PHP application logs errors to CloudWatch.
- MySQL logs connection failures.
- Nginx logs all HTTP requests.

## **API Endpoints & Usage**
| Method | Endpoint | Description |
|--------|-------------|-------------|
| GET | `/` | Renders the dashboard |
| POST | `/submit` | creates a new contacts and stores it in the database |
| GET | `/contacts` | Fetches stored contacts |

## **Troubleshooting & Common Issues**
### **1. AWS CloudWatch Log Group Not Found**
- Ensure the log group exists before deploying.
- Manually create the log group in **AWS Console → CloudWatch → Log Groups**.

### **2. High CPU Utilization on EC2**
- Use `htop` or `top` to identify process load.
- Optimize PHP-FPM settings for better performance.

### **3. ECS Deployment Failing**
- Verify IAM permissions for ECS and CloudWatch.
- Check logs using:
  ```sh
  aws logs describe-log-streams --log-group-name php-logs
  ```

## **Future Improvements**
- Implement **Prometheus & Grafana** for enhanced visualization.
- Automate **CI/CD pipeline** with GitHub Actions.
- Optimize **auto-scaling strategies** for ECS clusters.

## **Contributors**
- **@chris-zano** - Developer & Architect

## **License**
This project is licensed under the **MIT License**.

---
**Happy Monitoring!**

