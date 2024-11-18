import React from 'react';
import { Navigate } from 'react-router-dom';

const PrivateRoute = ({ element }) => {
  const isAuthenticated = localStorage.getItem('isAuthenticated'); // Replace with actual auth check

  return isAuthenticated ? element : <Navigate to="/login" />;
};

export default PrivateRoute;
