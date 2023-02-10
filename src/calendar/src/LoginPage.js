import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import './LoginPage.css';



function LoginPage() {
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const navigate = useNavigate();

  function handleUsernameChange(event) {
    setUsername(event.target.value);
  }

  function handlePasswordChange(event) {
    setPassword(event.target.value);
  }

  function handleSubmit(event) {
    event.preventDefault();
    const requestOptions = {
      method: "POST",
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        username: username,
        password: password
      })
    }
    fetch("/api/auth/token", requestOptions)
      .then(response => {
        if (response.status === 401) {
          // Username or password incorrect
          response.json().then(data => {
            console.log(data);
          })
        } else {
          response.json().then(data => {
            console.log(data);

            // Parse role to indicate landing page
            let jwtPayload = document.cookie
              .split('; ')
              .find(row => row.startsWith('jwtPayload='))
              .split('=')[1];
            let role = JSON.parse(atob(jwtPayload))["role"];

            // Navigate to correct landing page
            switch (role.toLowerCase()) {
              case "admin":
                navigate('/AdminLandingPage');
                break;
              case "attendee":
                navigate('/AttendeeLandingPage');
                break;
              case "instructor":
                navigate('/InstructorLandingPage');
                break;
              default:
                console.log(`Role not recognized: ${role.toLowerCase()}`);
                break;
            }
          })
        }
      })
      .catch(error => {
        console.error('There has been a problem with your fetch operation:', error);
      });
  }

  return (
    <div className="loginPage">
      <h1>NC DPR Training Calendar</h1>
      <div className="loginForm">
        <form onSubmit={handleSubmit}>
          <label>
            Username:
            <input type="text" value={username} onChange={handleUsernameChange} />
          </label>
          <label>
            Password:
            <input type="password" value={password} onChange={handlePasswordChange} />
          </label>
          <input type="submit" value="Submit" />
        </form>
      </div>
    </div>
  );
}

export default LoginPage;
