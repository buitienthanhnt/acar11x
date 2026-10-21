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
        <CategoryItems items={categories?.data as unknown as CategoryType[]} />
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
    <div className="grid grid-cols-3 gap-2 md:gap-4">
      {items.map((item, index) => {
        return (
          <div key={index} className="flex space-x-2">
            <img src={item.image_path} alt={item.name} className="size-24 object-cover rounded-full border border-gray-600" />
            <div>
              <p className={'font-semibold'}>{item.name}- {item.description}</p>
              <p>{item.active.toString()}</p>
            </div>
          </div>
        )
      })}
    </div>
  )
}