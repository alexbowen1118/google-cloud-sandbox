import React from 'react';
import {useDropzone} from 'react-dropzone';
import './FileDrop.css';

function FileDrop(props) {
  
  const onDrop = React.useCallback(acceptedFiles =>{
    props.fileUploaded(acceptedFiles);
  }, []);


  const {acceptedFiles, getRootProps, getInputProps, isDragActive, isDragReject} = useDropzone({
    onDrop, 
    accept: {'image/*': ['.jpg', '.png'],
      'text/*': ['.txt'], 
      'application/pdf': ['.pdf'],
      'application/vnd.openxmlformats-officedocument.wordprocessingml.document' : ['.docx'],
      'application/vnd.ms-excel': ['.xls']

    }, 
    multiple: false,
    maxSize: 5e+9,
    maxFiles: 1


  });
  
  
  const files = acceptedFiles.map(file => (
    <li key={file.path}>
      {file.path}
    </li>
  ));

  return (
    <section className="container">
      <div className="dropbox">
        <div {...getRootProps({className: 'dropzone'})}>
          <input {...getInputProps()}></input>
          <p id = "uploadFilesText">
            {!isDragActive && 'Click here or drop a file to upload!'}
            {isDragActive && !isDragReject && "Upload file!"}
            {isDragReject && "File type not accepted, sorry!"}
          </p>
          {/* <p id = "uploadFilesText1">(Only .jpg, .png, .pdf, .docx, .xls files are accepted) </p> */}
        </div>
      </div>
      <aside>
        <h4>Uploaded File</h4>
        <ul id ="fileList">{files}</ul>
      </aside>
    </section>
  );
}

export default FileDrop;