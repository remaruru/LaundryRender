// API Configuration
const getApiBaseUrl = () => {
  const envUrl = process.env.REACT_APP_API_URL;
  
  // If no environment variable, default to localhost
  if (!envUrl) {
    return 'http://localhost:8000';
  }
  
  // Ensure URL has protocol (https:// or http://)
  if (envUrl.startsWith('http://') || envUrl.startsWith('https://')) {
    return envUrl;
  }
  
  // If no protocol, add https://
  return `https://${envUrl}`;
};

const API_BASE_URL = getApiBaseUrl();

export const API_URL = API_BASE_URL;
export const API_ENDPOINT = `${API_BASE_URL}/api`;

export default API_ENDPOINT;

