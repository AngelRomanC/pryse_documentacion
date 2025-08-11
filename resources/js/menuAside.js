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

  {
    label: "Gestión de Usuarios",
    icon: mdiAccountGroup ,
    role: "Admin",
    menu: [
      {
        href:"/usuarios",
        label: "Admin",
        icon: mdiSecurity,
        role: "Admin",
      }, 
      {
        href:"/usuarios-sistema",
        label: "Desarrolladores",
        icon: mdiAccountTie   ,
        role: "Admin",
      }, 
      
    ]
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
