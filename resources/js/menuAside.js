import {
  mdiMonitor,
  mdiSecurity,
  mdiBookshelf,
  mdiFileDocumentOutline,
  mdiAccountTie ,mdiAccountSchool,
  mdiAccountGroup,
  mdiScaleBalance,
  mdiFileSign,
  mdiLibrary,
  mdiFormatListChecks, 
  mdiGavel , 
  mdiLaptop,
} from "@mdi/js";

export default [
  {
    href: "/dashboard",
    to: "/dashboard",
    icon: mdiMonitor,
    label: "Dashboard",
    role: "Admin" //Vistas 
  },
  // ... existing code ...
{
  label: "Seguridad",
  icon: mdiScaleBalance,
  role: "Admin",
  menu: [
    {
        href:"/usuarios",
        label: "Usuarios",
        icon: mdiSecurity,
        role: "Admin",
    }, 
    {
      route: "roles.index",
      label: "Roles",
      icon: mdiFormatListChecks,
      role: "Admin",
    },
    
    {
      route: "modules.index",
      label: "Módulos",
      icon: mdiFormatListChecks,
      role: "Admin",
    },
    {
      route: "permisos.index",
      label: "Permisos",
      icon: mdiFormatListChecks,
      role: "Admin",
    }, 
  ],
},
     
 {
    label: "Catálogos",
    icon: mdiLibrary ,
    role: "Admin",
    menu: [     
      {
        route: "marcas.index",
        label: "Registrar Marcas",
        icon: mdiLaptop ,
        role: "Admin",
      }, 
      {
        route: "departamentos.index",
        label: "Departamentos",
        icon: mdiLaptop ,
        role: "Admin",
      }, 
    ]
  },
  
    {
    route: "inventario.index",
    label: "Inventario Equipos",
    icon: mdiGavel ,
    role: "Admin",
  }, 

    {
    route: "sistema.index",
    label: "Inventario Sistema",
    icon: mdiGavel ,
    role: "Admin",
  }, 

  
 

  //Modulos a Desarrollador de sistema 
  {
    href: "/dashboard",
    to: "/dashboard",
    icon: mdiMonitor,
    label: "Dashboard",
    role: "Desarrollador" 
  },
  {
    label: "Catálogos",
    icon: mdiLibrary ,
    role: "Desarrollador",
    menu: [     
      {
        route: "marcas.index",
        label: "Registrar Marcas",
        icon: mdiLaptop ,
        role: "Desarrollador",
      }, 
    ]
  },
  {
    route: "inventario.index",
    //href:"/inventario",
    label: "Inventario Equipos",
    icon: mdiGavel ,
    role: "Desarrollador",
  }, 

  {
    route: "sistema.index",
    //href:"/inventario",
    label: "Inventario Sistema",
    icon: mdiGavel ,
    role: "Desarrollador",
  }, 

   //Modulos a Procesos de sistema 
  {
    href: "/dashboard",
    to: "/dashboard",
    icon: mdiMonitor,
    label: "Dashboard",
    role: "Procesos" 
  },
    {
    label: "Catálogos",
    icon: mdiLibrary ,
    role: "Procesos",
    menu: [     
   
      {
        route: "departamentos.index",
        label: "Departamentos",
        icon: mdiLaptop ,
        role: "Procesos",
      }, 
    ]
  },

  {
    route: "procesos.index",
    label: "Procesos",
    icon: mdiGavel ,
    role: "Procesos",
  }, 

  {
    route: "certificacion.index",
    label: "Certificaciones",
    icon: mdiGavel ,
    role: "Procesos",
  },
  
  //Modulos a Ejecutivo de sistema 
  {
    href: "/dashboard",
    to: "/dashboard",
    icon: mdiMonitor,
    label: "Dashboard",
    role: "Ejecutivo" 
  },




];
