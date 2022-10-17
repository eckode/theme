import { DbId } from ".";
import { Model as ContentModel } from "./content";

type ThunkStatus = "idle" | "loading" | "failed";

interface TypesObject {
  [k: string]: Array<DbId>;
}

interface AllObject {
  [k: DbId]: ContentModel | {};
}

interface Content {
  types: TypesObject,
  all: AllObject,
  status: ThunkStatus,
}

export interface State {
  content: Content;
}

export default {};
