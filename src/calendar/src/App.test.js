import { fireEvent, render, screen } from '@testing-library/react';
import App from './App';

test('renders login page', () => {
  render(<App />);
  
  expect(screen.getByText('Username:')).toBeInTheDocument();
  expect(screen.getByText('Password:')).toBeInTheDocument();
  expect(screen.getByText('NC DPR Training Calendar')).toBeInTheDocument();
});

test('login page does not login with incorrect credentials', () => {
  render(<App />);

  const username = screen.getByLabelText('Username:');
  const password = screen.getByLabelText('Password:');
  const submit = screen.getByText('Submit');

  fireEvent.change(username, {target: {value: 'user2'}});
  fireEvent.change(password, {target: {value: 'pw'}});
  fireEvent.click(submit);

  expect(screen.getByText('NC DPR Training Calendar')).toBeInTheDocument();
});

test('login page does login with correct credentials', () => {
  render(<App />);

  const username = screen.getByLabelText('Username:');
  const password = screen.getByLabelText('Password:');
  const submit = screen.getByText('Submit');

  fireEvent.change(username, {target: {value: 'user1'}});
  fireEvent.change(password, {target: {value: 'pw1'}});
  fireEvent.click(submit);

  expect(screen.getByText('Admin Home Page')).toBeInTheDocument();
});
