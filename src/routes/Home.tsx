import React from "react";
import { useLocation, useParams } from "react-router-dom";

const Home = (): JSX.Element => {
  const params = useParams();
  const location = useLocation();
  return (
    <div>
      <h1>Home</h1>
      {JSON.stringify({ params, location }, null, 2)}
    </div>
  );
};

export default Home;
