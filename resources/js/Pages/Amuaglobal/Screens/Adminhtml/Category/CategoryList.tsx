import { Link } from "@inertiajs/react";
import BaseLayout from "../Layout/BaseLayout";
import { PaginateInterface } from "@/Pages/Amuaglobal/types/Paginate";
import { PageWithLayout } from "@/Pages/Amuaglobal/types/PageWithLayout";
import LinkBtn from "@/Pages/Amuaglobal/Components/LinkBtn";
import { CategoryType } from "@/Pages/Amuaglobal/types/Category";

interface Props {
  categories: PaginateInterface
}


const CategoryList: PageWithLayout<Props> = ({ categories }) => {

  return (
    <div className="py-4 space-y-2">
      <div className="flex space-x-2">
        <LinkBtn path={'/adminhtml/amua-category/create'} title={'create category'} className="text-green-500" />
      </div>
      <div>
        <CategoryItems items={categories?.data} />
      </div>
    </div>
  )
}
CategoryList.layout = (page: any) => <BaseLayout children={page} title={'categories'}></BaseLayout>;
export default CategoryList;

const CategoryItems = ({ items }: { items: CategoryType[] }) => {
  if (!items) {
    return null;
  }

  return (
    <div className="flex flex-col gap-2">
      {items.map((item, index) => {
        return (
          <div key={index} className="flex gap-2">
            <img src={item.image_path} alt={item.name} className="size-24 object-cover rounded-full border border-gray-600" />
            <p className={'font-semibold'}>{item.name}- {item.description} - {item.active.toString()} </p>
          </div>
        )
      })}
    </div>
  )
}