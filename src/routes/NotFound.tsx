import React from "react";
import { useParams } from "react-router-dom";

const NotFound = (): JSX.Element => {
  const params = useParams();
  return (
    <div>
      <h1>404: Not Found</h1>
      <strong>{params.base}</strong> does not exist.
    </div>
  );
};

export default NotFound;
