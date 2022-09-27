import * as ContentType from "./ContentType.d";

// A mock function to sss making an async request for data
export async function fetchContent(
  endpoint: ContentType.Endpoint,
): Promise<[ContentType.Endpoint, ContentType.IdRow]> {
  const content = await fetch(
    `https://jaredrethman.com/wp-json/wp/v2/${endpoint}`,
  );
  return [endpoint, await content.json()];
}

const API = {
  fetchContent,
};

export default API;
