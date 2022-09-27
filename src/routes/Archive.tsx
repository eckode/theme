import React from "react";
import { useLocation, useParams } from "react-router-dom";

const Archive = (): JSX.Element => {
  const params = useParams();
  const location = useLocation();
  return (
    <div>
      <h1>Archive</h1>
      {JSON.stringify({ params, location }, null, 2)}
    </div>
  );
};

export default Archive;
