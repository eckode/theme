import { ContentModel, ContextValues, DbId } from "../../types/index";
// import { Model as ContentModel } from "../../types/content";

type ThunkStatus = "idle" | "loading" | "failed";

export interface TypesObject {
  [k: string]: Array<Array<DbId>>;
}

export interface AllObject {
  [k: DbId]: ContentModel;
}

export interface State {
  types: TypesObject,
  all: AllObject,
  status: ThunkStatus,
  boot: boolean;
}

export interface FetchAsyncArgs { endpoint: ContextValues; page?: number };

export interface FetchAsyncResponse extends FetchAsyncArgs {
  payload: ContentModel[];
};

// declare function (x?: number): void;

export default {};