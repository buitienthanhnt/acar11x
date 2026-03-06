import { AttrInterface } from "@/Pages/Amuaglobal/types/Attr";
import { BookCateInterface } from "./BookCategory";
import { GalleryInterface } from "@/Pages/Amuaglobal/types/Gallery";
import { OrderInterface } from "@/Pages/Amuaglobal/types/Order";

export type BookItemType = {
  id: number;
  name: string;
  price: number;
  image_path?: string;
  description?: string;
  url: string;
}

export type BookDetailType = {
  attr: AttrInterface[];
  book_cate?: BookCateInterface[];
  gallery?: GalleryInterface[];
  qty: number;
  // bookOrders?: OrderInterface[];
} & BookItemType