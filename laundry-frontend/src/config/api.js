// API Configuration
const getApiBaseUrl = () => {
  const envUrl = process.env.REACT_APP_API_URL;
  
  // If no environment variable, default to localhost
  if (!envUrl) {
    return 'http://localhost:8000';
  }
  
  // Ensure URL has protocol (https:// or http://)
  if (envUrl.startsWith('http://') || envUrl.startsWith('https://')) {
    // Check if it has .onrender.com, if not add it
    if (envUrl.includes('laundry-backend') && !envUrl.includes('.onrender.com')) {
      return envUrl.replace('laundry-backend', 'laundry-backend-t63y.onrender.com');
    }
    return envUrl;
  }
  
  // If no protocol, add https://
  let url = `https://${envUrl}`;
  // If it's just the service name, add .onrender.com
  if (url.includes('laundry-backend') && !url.includes('.onrender.com')) {
    url = 'https://laundry-backend-t63y.onrender.com';
  }
  return url;
};

const API_BASE_URL = getApiBaseUrl();

export const API_URL = API_BASE_URL;
export const API_ENDPOINT = `${API_BASE_URL}/api`;

export default API_ENDPOINT;

