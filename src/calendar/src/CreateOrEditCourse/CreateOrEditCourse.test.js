import { BrowserRouter as Router } from 'react-router-dom';
import { render, screen, fireEvent, within } from '@testing-library/react';
import CreateOrEditCourse from './CreateOrEditCourse.js';

test('all labels are displayed', () => {
  render(<Router><CreateOrEditCourse /></Router>);

  const courseName = screen.getByLabelText('Course Name:');
  const category = screen.getByText('Category:');
  const description = screen.getByLabelText('Description:');
  const reqs = screen.getByLabelText('Requirements:');

  expect(courseName).toBeInTheDocument();
  expect(category).toBeInTheDocument();
  expect(description).toBeInTheDocument();
  expect(reqs).toBeInTheDocument();
});

test('submit and exit buttons are displayed', () => {
    render(<Router><CreateOrEditCourse /></Router>);
  
    const createCourse = screen.getByText('Save Course');
    const home = screen.getByText('Home');
  
    expect(createCourse).toBeInTheDocument();
    expect(home).toBeInTheDocument();
});

test('input boxes change when typed in', () => {
    render(<Router><CreateOrEditCourse /></Router>);

    const courseName = screen.getByLabelText('Course Name:');
    const description = screen.getByLabelText('Description:');
    const reqs = screen.getByLabelText('Requirements:');

    fireEvent.change(courseName, {target: {value: 'Fire Safety 101'}});
    expect(courseName.value).toBe('Fire Safety 101');

    fireEvent.change(description, {target: {value: 'Basic fire safety course'}});
    expect(description.value).toBe('Basic fire safety course');

    fireEvent.change(reqs, {target: {value: 'No prereqs for this course'}});
    expect(reqs.value).toBe('No prereqs for this course');

});

test('submit button works', () => {
    render( <Router><CreateOrEditCourse /></Router> );

    const courseName = screen.getByLabelText('Course Name:');
    const description = screen.getByLabelText('Description:');
    const reqs = screen.getByLabelText('Requirements:');

    fireEvent.change(courseName, {target: {value: 'Fire Safety 101'}});
    expect(courseName.value).toBe('Fire Safety 101');

    fireEvent.change(description, {target: {value: 'Basic fire safety course'}});
    expect(description.value).toBe('Basic fire safety course');

    fireEvent.change(reqs, {target: {value: 'No prereqs for this course'}});
    expect(reqs.value).toBe('No prereqs for this course');
  
    const createCourse = screen.getByText('Save Course');
  
    fireEvent.click(createCourse);
    
    expect(courseName.value).toBe('');
    expect(description.value).toBe('');
    expect(reqs.value).toBe('');
});

test('select tag displays all options', () => {
    render(<Router><CreateOrEditCourse /></Router>);

    const category = screen.getByLabelText('Category:');

    fireEvent.mouseDown(category);
    
    const safety = screen.getByText('Safety');
    const training = screen.getByText('Training');
    const other = screen.getByText('Other');

    expect(safety).toBeInTheDocument();
    expect(training).toBeInTheDocument();
    expect(other).toBeInTheDocument();
});

test('select tag does allow you to choose a valid option', () => {
    const {getByRole} = render(<Router><CreateOrEditCourse /></Router>);

    const category = screen.getByLabelText('Category:');
    
    fireEvent.mouseDown(category);

    const listbox = within(getByRole('listbox'));

    fireEvent.click(listbox.getByText('Safety'));

    expect(screen.getByLabelText('Safety')).toBeInTheDocument();
});
