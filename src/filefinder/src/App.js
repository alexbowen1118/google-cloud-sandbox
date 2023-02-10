// import './App.css'
import React  from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import UploadFiles from './components/UploadFiles/UploadFiles'
import PageHeader from './components/PageHeader/PageHeader';
import LoginPage from './LoginPage';
import SearchPage from './components/Search/SearchPage';
import ViewTopic from './components/Topic/ViewTopic';
import EditTopic from './components/Topic/EditTopic/EditTopic';
import AdminActions from './components/AdminActions/AdminActions';

function App() {
  return (
    <Router basename='/filefinder'>
      <div className='filefinderApp'>
        <div>
          <PageHeader/>
        </div>
        <Routes >
          <Route exact path='/' element={<LoginPage />} />
          <Route exact path='/uploadfiles' element={<UploadFiles/>} />
          <Route  exact path='/search' element={<SearchPage/>}/>
          <Route  exact path='/topic/:topicId' element={<ViewTopic/>}/>
          <Route exact path = '/topic/:topicId/edit' element={<EditTopic/>}/>
          <Route exact path = '/adminactions' element={<AdminActions/>}/>

        </Routes>
      </div>
    </Router>
  );
}

export default App;
