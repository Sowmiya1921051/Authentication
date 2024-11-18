import React, { useState, useEffect } from 'react';

const Register = () => {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    phone: '',
    password: '',
    confirmPassword: '',
  });
  const [message, setMessage] = useState('');
  const [isTyping, setIsTyping] = useState(false);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData({
      ...formData,
      [name]: value,
    });

    if (!isTyping) {
      setIsTyping(true);
      setTimeout(() => {
        setIsTyping(false);
      }, 2000);  // Disable animation for 2 seconds after typing
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    // Check if passwords match
    if (formData.password !== formData.confirmPassword) {
      setMessage('Passwords do not match');
      return;
    }

    try {
      const response = await fetch('http://localhost/nms/register.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData),
      });

      const result = await response.json();

      // Log the result to see what's returned from the backend
      console.log(result);

      if (result.success) {
        setMessage('Registration successful!');
        // Refresh the page after successful registration
        setTimeout(() => {
          window.location.reload();
        }, 1500);  // Delay page reload for a smoother experience
      } else {
        setMessage(result.message || 'Registration failed!');
      }
    } catch (error) {
      setMessage('An error occurred. Please try again.');
    }
  };

  useEffect(() => {
    // Add or remove the 'inactive' class based on typing status
    const formContainer = document.querySelector('.register-container');
    if (formContainer) {
      if (isTyping) {
        formContainer.classList.add('inactive');
      } else {
        formContainer.classList.remove('inactive');
      }
    }
  }, [isTyping]);

  return (
    <div className="register-container">
      <h2>Register</h2>
      <form onSubmit={handleSubmit}>
        <div className="form-group">
          <label>
            Name:
            <input
              type="text"
              name="name"
              value={formData.name}
              onChange={handleChange}
              required
            />
          </label>
        </div>
        <div className="form-group">
          <label>
            Email:
            <input
              type="email"
              name="email"
              value={formData.email}
              onChange={handleChange}
              required
            />
          </label>
        </div>
        <div className="form-group">
          <label>
            Phone Number:
            <input
              type="tel"
              name="phone"
              value={formData.phone}
              onChange={handleChange}
              required
            />
          </label>
        </div>
        <div className="form-group">
          <label>
            Password:
            <input
              type="password"
              name="password"
              value={formData.password}
              onChange={handleChange}
              required
            />
          </label>
        </div>
        <div className="form-group">
          <label>
            Confirm Password:
            <input
              type="password"
              name="confirmPassword"
              value={formData.confirmPassword}
              onChange={handleChange}
              required
            />
          </label>
        </div>
        <button className="submit-button" type="submit">Register</button>
      </form>

      {/* Display the message */}
      {message && (
        <p className={`message ${message.includes('successful') ? 'success' : 'error'}`}>
          {message}
        </p>
      )}
    </div>
  );
};

export default Register;
