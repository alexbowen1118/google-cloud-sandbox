import React, { useState } from 'react';
import './LoginPage.css';
import { useNavigate } from "react-router-dom";
import APIClient from "../../utils/APIClient";

function LoginPage(props) {

  const navigate = useNavigate();
  const [username, password] = React.useState('');
  const [credentials] = useState(props.device ? { ...props.device } : {
    username,
    password
  });

  const handleUsernameChange = (event) => {
    credentials.username = event.target.value
  };

  const handlePasswordChange = (event) => {
    credentials.password = event.target.value
  };

  function handleSubmit(event) {
    event.preventDefault();
    APIClient.Auth.login(credentials)
      .then(response => {
        if (response.status === 401) {
          // Username or password incorrect
          response.json().then(data => {
            console.log(data);
          })
        } else {
          // Parse role to indicate landing page
          let jwtPayload = document.cookie
            .split('; ')
            .find(row => row.startsWith('jwtPayload='))
            .split('=')[1];
          let role = JSON.parse(atob(jwtPayload))["role"];
          let park = JSON.parse(atob(jwtPayload))["park"]["id"];

          sessionStorage.setItem("role", role);
          sessionStorage.setItem("park", park);
          console.log(sessionStorage.getItem("role"));
          console.log(sessionStorage.getItem("park"));

          // Navigate to correct landing page
          navigate('/MainLandingPage');
        }
      })
      .catch(error => {
        console.error('There has been a problem with your fetch operation:', error);
      });
  }

  return (
    <div className="loginPage">
      <h1>NC DPR Login</h1>
      <div className="loginForm">
        <form onSubmit={handleSubmit}>
          <label>
            Username:
            <input required type="text" onChange={handleUsernameChange} />
          </label>
          <label>
            Password:
            <input required type="password" onChange={handlePasswordChange} />
          </label>
          <input type="submit" value="Submit" />
        </form>
      </div>
    </div>
  );
}

export default LoginPage;
