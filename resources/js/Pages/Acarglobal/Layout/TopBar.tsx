import { router } from "@inertiajs/react";
import { Button } from "@material-tailwind/react";

const topBarAction = [
  {
    name: 'Trang chủ',
    path: '/acar',
  },
  {
    name: 'Xe vào',
    path: '/acar/xe-vao',
  },
  {
    name: 'Danh sách xe',
    path: '/acar/car-list',
  },
  {
    name: 'Chấm công',
    path: '/acar/cham-cong',
  },
  {
    name: 'Thống kê',
    path: '/acar/thong-ke',
  },
  {
    name: 'Phụ tùng',
    path: '/adminhtml/product/',
  }
];


export default function TopBar() {
  const path = window.location.pathname;

  return (
    <div className="grid grid-cols-6 p-1 gap-[1px] no-print">
      {topBarAction.map((action) => (
        <Button
          key={action.path}
          color={path === action.path ? "blue" : "gray"}
          variant="filled"
          className="!rounded-none"
          onClick={() => {
            router.visit(action.path);
          }}>
          {action.name}
        </Button>
      ))}
    </div>
  )
}