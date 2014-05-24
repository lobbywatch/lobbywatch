using System;
using System.Collections.Generic;
using System.Configuration;
using System.Data;
using System.Linq;
using System.Net;
using System.Text;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using MySql.Data.MySqlClient;

namespace VisualLobby
{
   public partial class LobbywatchData : System.Web.UI.Page
   {
      protected int _filterBranche = 0;
      protected int _filterKommission = 0;
      protected int _optionFunction1 = 0;
      protected int _optionFunction2 = 0;
      protected int _optionDeep1 = 0;
      protected int _optionDeep2 = 0;
      protected string _connectString = "";
      protected string _filterOrganisation = "%";
      protected string _filterPerson = "%";

      protected void Page_Load(object sender, EventArgs e)
      {
         if (Request["Option"] == null)
            return;

         if (Request["filterBranche"] != null)
         {
            Int32.TryParse(Request["filterBranche"].ToString(), out _filterBranche);
         }
         if (Request["filterKommission"] != null)
         {
            Int32.TryParse(Request["filterKommission"].ToString(), out _filterKommission);
         }
         if (Request["optionFunction1"] != null)
         {
            Int32.TryParse(Request["optionFunction1"].ToString(), out _optionFunction1);
         }
         if (Request["optionFunction2"] != null)
         {
            Int32.TryParse(Request["optionFunction2"].ToString(), out _optionFunction2);
         }
         if (Request["optionDeep1"] != null)
         {
            Int32.TryParse(Request["optionDeep1"].ToString(), out _optionDeep1);
         }
         if (Request["optionDeep2"] != null)
         {
            Int32.TryParse(Request["optionDeep2"].ToString(), out _optionDeep2);
         }
         if (Request["filterOrganisation"] != null)
         {
            _filterOrganisation = Request["filterOrganisation"].ToString();
            if (!string.IsNullOrEmpty(_filterOrganisation))
            {
               _filterOrganisation = _filterOrganisation.Replace(' ', '%');
            }
            _filterOrganisation = "%" + _filterOrganisation + "%";
         }
         if (Request["filterPerson"] != null)
         {
            _filterPerson = Request["filterPerson"].ToString();
            if (!string.IsNullOrEmpty(_filterPerson))
            {
               _filterPerson = _filterPerson.Replace(' ', '%');
            }
            _filterPerson = "%" + _filterPerson + "%";
         }

         if (ConfigurationManager.ConnectionStrings["LobbywatchDb"] != null)
         {
            _connectString = ConfigurationManager.ConnectionStrings["LobbywatchDb"].ConnectionString;
         }

         if (string.IsNullOrEmpty(_connectString))
         {
            string msg = "no database connection configured!";
            Response.Write(msg);
            Response.End();
         }

         string cmd = Request["Option"].ToString();
         if (cmd == "GetParlamentData")
         {
            if (Request["format"] != null)
            {
               string format = Request["format"].ToString();
               Response.Clear();
               if (string.Equals(format, "xml", StringComparison.OrdinalIgnoreCase))
               {
                  Response.Write(GetParlamentDataXML());
               }
               else
               {
                  Response.Write(GetParlamentDataJSON());
               }
               Response.End();
            }
         }
         else if (cmd == "GetParlamentarierNetworkData")
         {
            if (Request["id"] != null && Request["format"] != null)
            {
               int parlamentarierId = 0;
               Int32.TryParse(Request["id"].ToString(), out parlamentarierId);

               string format = Request["format"].ToString();

               Response.Clear();
               if (string.Equals(format, "xml", StringComparison.OrdinalIgnoreCase))
               {
                  Response.Write(GetParlamentarierNetworkDataXML(parlamentarierId));
               }
               else if (string.Equals(format, "json", StringComparison.OrdinalIgnoreCase))
               {
                  Response.Write(GetParlamentarierNetworkDataJSON(parlamentarierId));
               }
               Response.End();
            }
         }
         else if (cmd == "GetOrganisationNetworkData")
         {
            if (Request["id"] != null && Request["format"] != null)
            {
               int organisationId = 0;
               Int32.TryParse(Request["id"].ToString(), out organisationId);

               string format = Request["format"].ToString();

               Response.Clear();

               if (string.Equals(format, "xml", StringComparison.OrdinalIgnoreCase))
               {
                  Response.Write(GetOrganisationNetworkDataXML(organisationId));
               }
               else if (string.Equals(format, "json", StringComparison.OrdinalIgnoreCase))
               {
                  Response.Write(GetOrganisationNetworkDataJSON(organisationId));
               }
               Response.End();
            }
         }
      }

      // helper functions
      #region private methods
      private string ReplaceQuotationMark(string s)
      {
         return s.Replace("\"", "\\\"");
      }
      #endregion

      #region public methods
      #endregion

      protected string GetOrganisationDataXML(int organisationId, bool recursive, MySqlConnection conn)
      {
         StringBuilder xmlData = new StringBuilder();

         string cmd = "";

         /*
         select o1.id as o1_id, o1.name_de as o1_name, o1.typ as o1_typ, b1.name as o1_branche, 
         o2.id as o2_id, o2.name_de as o2_name, o2.typ as o2_typ, b2.name as o2_branche, ob.art as art 
         from organisation o1
         inner join organisation_beziehung ob on ob.organisation_id = o1.id
         inner join organisation o2 on ob.ziel_organisation_id = o2.id
         left join branche b1 on b1.id = o1.branche_id
         left join branche b2 on b2.id = o2.branche_id
         where o1.id = 412 or o2.id = 412
          */

         //
         // read Parlamentarier interessenbindung data
         //
         cmd = "select o1.id as o1_id, o1.name_de as o1_name, o1.typ as o1_typ, b1.name as o1_branche, " +
               "o2.id as o2_id, o2.name_de as o2_name, o2.typ as o2_typ, b2.name as o2_branche, ob.art as art " +
               "from organisation o1 " +
               "inner join organisation_beziehung ob on ob.organisation_id = o1.id " +
               "inner join organisation o2 on ob.ziel_organisation_id = o2.id " +
               "left join branche b1 on b1.id = o1.branche_id " +
               "left join branche b2 on b2.id = o2.branche_id " +
               "where o1.id = @organisationId or o2.id = @organisationId ";

         using (MySqlCommand command = new MySqlCommand(cmd, conn))
         {
            command.Parameters.AddWithValue("@organisationId", organisationId);

            MySqlDataReader reader = command.ExecuteReader();

            DataTable dt = new DataTable();

            dt.Load(reader);

            foreach (DataRow row in dt.Rows)
            {
               int suborganisationId = 0;

               Int32.TryParse(row["o1_id"].ToString(), out suborganisationId);

               if (suborganisationId > 0 && suborganisationId != organisationId)
               {
                  xmlData.Append("<organisation>");
                  xmlData.Append("<name_de>" + WebUtility.HtmlEncode(row["o1_name"].ToString()) + "</name_de>");
                  xmlData.Append("<typ>" + WebUtility.HtmlEncode(row["o1_typ"].ToString()) + "</typ>");
                  xmlData.Append("<branche>" + WebUtility.HtmlEncode(row["o1_branche"].ToString()) + "</branche>");

                  xmlData.Append(GetOrganisationDataXML(suborganisationId, true, conn));

                  xmlData.Append("</organisation>");
               }

               Int32.TryParse(row["o2_id"].ToString(), out suborganisationId);

               if (suborganisationId > 0 && suborganisationId != organisationId)
               {
                  xmlData.Append("<organisation>");
                  xmlData.Append("<name_de>" + WebUtility.HtmlEncode(row["o2_name"].ToString()) + "</name_de>");
                  xmlData.Append("<typ>" + WebUtility.HtmlEncode(row["o2_typ"].ToString()) + "</typ>");
                  xmlData.Append("<branche>" + WebUtility.HtmlEncode(row["o2_branche"].ToString()) + "</branche>");

                  xmlData.Append(GetOrganisationDataXML(suborganisationId, true, conn));

                  xmlData.Append("</organisation>");
               } 
            }
         }

         return xmlData.ToString();
      }

      protected DataTable GetParlamentData()
      {
         DataTable dtRet = null;

         /*
         select count(*) as anzahl, pa.abkuerzung as partei, p.ratstyp as ratstyp, pa.name as name_de, f.abkuerzung as fraktion from parlamentarier p
         inner join partei pa on pa.id = p.partei_id
         left join fraktion f on f.id = p.fraktion_id
         group by pa.abkuerzung, p.ratstyp, pa.name, f.name
         order by 3 asc, 1 desc, 2 asc 
         */

         try
         {
            using (MySqlConnection conn = new MySqlConnection(_connectString))
            {
               conn.Open();

               string cmd = "";

               //
               // read Parlament basis data
               //
               cmd = "select count(*) as anzahl, pa.abkuerzung as partei_short, pa.name as partei_name_de, f.abkuerzung as fraktion " +
                     "from parlamentarier p " +
                     "inner join partei pa on pa.id = p.partei_id " +
                     "left join fraktion f on f.id = p.fraktion_id " +
                     "where " +
                     "(@filterKommission = 0 or exists (select * from in_kommission ik where ik.parlamentarier_id = p.id and ik.kommission_id = @filterKommission)) " +
                     "and concat(p.vorname, ' ', p.nachname) like @filterPerson " +
                     "group by pa.abkuerzung, pa.name " +
                     "order by count(*) desc, pa.abkuerzung asc";

               using (MySqlCommand command = new MySqlCommand(cmd, conn))
               {
                  command.Parameters.AddWithValue("@filterKommission", _filterKommission);
                  command.Parameters.AddWithValue("@filterBranche", _filterBranche);
                  command.Parameters.AddWithValue("@filterPerson", _filterPerson);
                  MySqlDataReader reader = command.ExecuteReader();
                  dtRet = new DataTable();
                  dtRet.Load(reader);
               }
            }
         }
         catch (Exception e)
         {
            string msg = e.Message;
            Response.Write(msg);
            Response.End();
         }

         return dtRet;
      }

      public string GetParlamentDataXML()
      {
         string ret = "";

         //select count(*) as anzahl, pa.abkuerzung as partei, p.ratstyp as ratstyp, pa.name as name_de, f.abkuerzung as fraktion from parlamentarier p
         //inner join partei pa on pa.id = p.partei_id
         //left join fraktion f on f.id = p.fraktion_id
         //group by pa.abkuerzung, p.ratstyp, pa.name, f.name
         //order by 3 asc, 1 desc, 2 asc 

         // color Fraktionen
         // SVP: #0A7D3A
         // SP: #FF0505
         // CVP: #FB7407
         // FDP: #0A4BD6
         // GP: #07F61E
         // GLP: #88487F
         // BDP: #FFE543
         // ohne: #c0c0c0

         Dictionary<string, string> mapFraktionColor = new Dictionary<string, string>();

         mapFraktionColor.Add("V", "#0A7D3A");
         mapFraktionColor.Add("S", "#FF0505");
         mapFraktionColor.Add("CE", "#FB7407");
         mapFraktionColor.Add("RL", "#0A4BD6");
         mapFraktionColor.Add("G", "#07F61E");
         mapFraktionColor.Add("GL", "#88487F");
         mapFraktionColor.Add("BD", "#FFE543");
         mapFraktionColor.Add("-NONE-", "#c0c0c0");

         try
         {
            using (MySqlConnection conn = new MySqlConnection(_connectString))
            {
               conn.Open();

               string cmd = "";


               string ratstyp = "";

               StringBuilder xmlData = new StringBuilder();

               xmlData.Append("<?xml version=\"1.0\" encoding=\"UTF-8\" ?>");

               xmlData.Append("<parlamentdata>");

               xmlData.Append("<id>" + "ch" + "</id>");
               xmlData.Append("<country>" + "CH" + "</country>");


               cmd = "select count(*) as anzahl, pa.abkuerzung as partei_short, pa.name as partei_name_de, f.abkuerzung as fraktion " +
                     "from parlamentarier p " +
                     "inner join partei pa on pa.id = p.partei_id " +
                     "left join fraktion f on f.id = p.fraktion_id " +
                     "group by pa.abkuerzung, pa.name " +
                     "order by count(*) desc, pa.abkuerzung asc";

               using (MySqlCommand command = new MySqlCommand(cmd, conn))
               {
                  MySqlDataReader reader = command.ExecuteReader();

                  DataTable dt = new DataTable();

                  dt.Load(reader);


                  xmlData.Append("<parlament>");

                  xmlData.Append("<totalMembers>" + "246" + "</totalMembers>");

                  foreach (DataRow row in dt.Rows)
                  {
                     xmlData.Append("<partei>");

                     string partei = row["partei_short"].ToString();

                     xmlData.Append("<id>" + partei + "</id>");
                     xmlData.Append("<name_de>" + WebUtility.HtmlEncode(row["partei_name_de"].ToString()) + "</name_de>");
                     xmlData.Append("<totalMembers>" + row["anzahl"].ToString() + "</totalMembers>");

                     string color = "#e0e0e0";
                     string fraktion = (!string.IsNullOrEmpty(row["fraktion"].ToString())) ? row["fraktion"].ToString() : "-NONE-";

                     if (mapFraktionColor.ContainsKey(fraktion))
                     {
                        color = mapFraktionColor[fraktion];
                     }
                     xmlData.Append("<color>" + color + "</color>");

                     xmlData.Append(GetParlamentarierOnPartei(ratstyp, partei, conn));

                     xmlData.Append("</partei>");
                  }


                  xmlData.Append("</parlament>");
               }


               xmlData.Append("</parlamentdata>");

               ret = xmlData.ToString();


            }
         }
         catch (Exception e)
         {
            string msg = e.Message;
            Response.Write(msg);
            Response.End();
         }

         return ret;
      }

      public string GetParlamentDataJSON()
      {
         string ret = "";

         // color Fraktionen
         // SVP: #0A7D3A
         // SP: #FF0505
         // CVP: #FB7407
         // FDP: #0A4BD6
         // GP: #07F61E
         // GLP: #88487F
         // BDP: #FFE543
         // ohne: #c0c0c0

         Dictionary<string, string> mapFraktionColor = new Dictionary<string, string>();

         mapFraktionColor.Add("V", "#0A7D3A");
         mapFraktionColor.Add("S", "#FF0505");
         mapFraktionColor.Add("CE", "#FB7407");
         mapFraktionColor.Add("RL", "#0A4BD6");
         mapFraktionColor.Add("G", "#07F61E");
         mapFraktionColor.Add("GL", "#88487F");
         mapFraktionColor.Add("BD", "#FFE543");
         mapFraktionColor.Add("-NONE-", "#c0c0c0");

         try
         {
            StringBuilder data = new StringBuilder();

            data.Append("{");
            data.Append("\"parlamentdata\": {");

            data.Append("\"id\":\"" + "ch" + "\",");
            data.Append("\"country\":\"" + "CH" + "\",");

            data.Append("\"parlament\": {");
            data.Append("\"totalMembers\":\"" + "246" + "\",");

            DataTable dtParlament = GetParlamentData();

            if (dtParlament != null && dtParlament.Rows.Count > 0)
            {
               data.Append("\"partei\": [");
               
               foreach (DataRow rowParlament in dtParlament.Rows)
               {
                  string partei = rowParlament["partei_short"].ToString();

                  data.Append("{");

                  data.Append("\"id\":\"" + partei + "\",");
                  data.Append("\"name_de\":\"" + rowParlament["partei_name_de"].ToString() + "\",");
                  data.Append("\"totalMembers\":\"" + rowParlament["anzahl"].ToString() + "\",");

                  string color = "#e0e0e0";
                  string fraktion = (!string.IsNullOrEmpty(rowParlament["fraktion"].ToString())) ? rowParlament["fraktion"].ToString() : "-NONE-";

                  if (mapFraktionColor.ContainsKey(fraktion))
                  {
                     color = mapFraktionColor[fraktion];
                  }
                  data.Append("\"color\":\"" + color + "\",");

                  DataTable dtParlamentarier = GetParlamentarierOnParteiData(partei);
                  if (dtParlamentarier != null && dtParlamentarier.Rows.Count > 0)
                  {
                     data.Append("\"members\": [");

                     foreach (DataRow rowParlamentarier in dtParlamentarier.Rows)
                     {
                        data.Append("{");

                        data.Append("\"id\":\"" + rowParlamentarier["id"].ToString() + "\",");
                        data.Append("\"name\":\"" + rowParlamentarier["name"].ToString() + "\",");
                        data.Append("\"firstname\":\"" + rowParlamentarier["firstname"].ToString() + "\",");
                        data.Append("\"ratstyp\":\"" + rowParlamentarier["ratstyp"].ToString() + "\",");

                        int parlamentarierId = 0;
                        Int32.TryParse(rowParlamentarier["id"].ToString(), out parlamentarierId);

                        DataTable dtRelationsCount = GetParlamentarierRelationsCount(parlamentarierId);
                        if (dtRelationsCount != null && dtRelationsCount.Rows.Count > 0)
                        {
                           DataRow rowRelationsCount = dtRelationsCount.Rows[0];
                           data.Append("\"relations\":\"" + rowRelationsCount["anzahlTotal"].ToString() + "\",");
                           data.Append("\"relations_simple\":\"" + rowRelationsCount["anzahlSimple"].ToString() + "\",");
                           data.Append("\"relations_executive\":\"" + rowRelationsCount["anzahlExecutive"].ToString() + "\",");
                        }
                        dtRelationsCount = GetParlamentarierGastRelationsCount(parlamentarierId);
                        if (dtRelationsCount != null && dtRelationsCount.Rows.Count > 0)
                        {
                           DataRow rowRelationsCount = dtRelationsCount.Rows[0];
                           data.Append("\"gast_relations\":\"" + rowRelationsCount["anzahlTotal"].ToString() + "\",");
                           data.Append("\"gast_relations_simple\":\"" + rowRelationsCount["anzahlSimple"].ToString() + "\",");
                           data.Append("\"gast_relations_executive\":\"" + rowRelationsCount["anzahlExecutive"].ToString() + "\",");
                        }

                        // remove trailing ','
                        if (data.ToString().EndsWith(",")) data = data.Remove(data.Length - 1, 1);
                        data.Append("},");
                     }

                     // remove trailing ','
                     if (data.ToString().EndsWith(",")) data = data.Remove(data.Length - 1, 1);
                     data.Append("]");    // end 'members'
                  }

                  data.Append("},");
               }

               // remove trailing ','
               if (data.ToString().EndsWith(",")) data = data.Remove(data.Length - 1, 1);
               data.Append("]");    // end 'partei'
            }

            // remove trailing ','
            if (data.ToString().EndsWith(",")) data = data.Remove(data.Length - 1, 1);
            data.Append("}");    // end 'parlament'

            // remove trailing ','
            if (data.ToString().EndsWith(",")) data = data.Remove(data.Length - 1, 1);
            data.Append("}");    // end 'parlamentdata'
            data.Append("}");

            ret = data.ToString();
         }
         catch (Exception e)
         {
            string msg = e.Message;
            Response.Write(msg);
            Response.End();
         }

         return ret;
      }

      protected string GetParlamentarierOnPartei(string ratstyp, string partei, MySqlConnection conn)
      {
         StringBuilder xmlData = new StringBuilder();

         string cmd = "";

         /*
         select p.nachname as name, p.vorname as firstname, p.id as id
         from parlamentarier p
         inner join partei pa on pa.id = p.partei_id
         where p.ratstyp = 'NR' and pa.abkuerzung = 'SVP'
          */

         //
         // read Parlamentarier zu partei
         //
         cmd = "select p.nachname as name, p.vorname as firstname, p.id as id, p.ratstyp as ratstyp " +
               "from parlamentarier p " +
               "inner join partei pa on pa.id = p.partei_id " +
               "where pa.abkuerzung = @partei " +
               "order by p.nachname, p.vorname";

         using (MySqlCommand command = new MySqlCommand(cmd, conn))
         {
            command.Parameters.AddWithValue("@partei", partei);

            MySqlDataReader reader = command.ExecuteReader();

            DataTable dt = new DataTable();

            dt.Load(reader);

            foreach (DataRow row in dt.Rows)
            {
               xmlData.Append("<members>");
               xmlData.Append("<id>" + row["id"].ToString() + "</id>");
               xmlData.Append("<name>" + WebUtility.HtmlEncode(row["name"].ToString()) + "</name>");
               xmlData.Append("<firstname>" + WebUtility.HtmlEncode(row["firstname"].ToString()) + "</firstname>");
               xmlData.Append("<ratstyp>" + WebUtility.HtmlEncode(row["ratstyp"].ToString()) + "</ratstyp>");

               int parlamentarierId = 0;
               Int32.TryParse(row["id"].ToString(), out parlamentarierId);
               xmlData.Append(GetParlamentarierRelations(parlamentarierId, conn));
               xmlData.Append(GetParlamentarierGastRelations(parlamentarierId, conn));

               xmlData.Append("</members>");
            }
         }

         return xmlData.ToString();
      }

      protected string GetParlamentarierRelations(int parlamentarierId, MySqlConnection conn)
      {
         StringBuilder xmlData = new StringBuilder();

         string cmd = "";

         int relations_total = 0;
         int relations_simple = 0;
         int relations_executive = 0;

         //
         // read Parlamentarier interessenbindung data
         //
         cmd = "select count(*) as anzahl " +
               "from interessenbindung ib " +
               "where ib.parlamentarier_id = @parlamentarierId ";

         using (MySqlCommand command = new MySqlCommand(cmd, conn))
         {
            command.Parameters.AddWithValue("@parlamentarierId", parlamentarierId);

            MySqlDataReader reader = command.ExecuteReader();

            DataTable dt = new DataTable();

            dt.Load(reader);

            if (dt.Rows.Count > 0)
            {
               string s = dt.Rows[0]["anzahl"].ToString();
               if (!string.IsNullOrEmpty(s))
               {
                  Int32.TryParse(s, out relations_total);
               }
            }
         }

         cmd = "select count(*) as anzahl " +
               "from interessenbindung ib " +
               "where ib.parlamentarier_id = @parlamentarierId " +
               "and art in ('vorstand', 'geschaeftsfuehrend')";

         using (MySqlCommand command = new MySqlCommand(cmd, conn))
         {
            command.Parameters.AddWithValue("@parlamentarierId", parlamentarierId);

            MySqlDataReader reader = command.ExecuteReader();

            DataTable dt = new DataTable();

            dt.Load(reader);

            if (dt.Rows.Count > 0)
            {
               string s = dt.Rows[0]["anzahl"].ToString();
               if (!string.IsNullOrEmpty(s))
               {
                  Int32.TryParse(s, out relations_executive);
               }
            }
         }

         relations_simple = relations_total - relations_executive;

         xmlData.Append("<relations>" + relations_total + "</relations>");
         xmlData.Append("<relations_simple>" + relations_simple + "</relations_simple>");
         xmlData.Append("<relations_executive>" + relations_executive + "</relations_executive>");

         return xmlData.ToString();
      }

      protected string GetParlamentarierGastRelations(int parlamentarierId, MySqlConnection conn)
      {
         StringBuilder xmlData = new StringBuilder();

         string cmd = "";

         int relations_total = 0;
         int relations_simple = 0;
         int relations_executive = 0;


         //
         // read Parlamentarier Gast interessenbindung data
         //

         // ALLE
         cmd = "select count(*) as anzahl " +
               "from zutrittsberechtigung zb " +
               "inner join mandat ma on ma.zutrittsberechtigung_id = zb.id "+
               "where zb.parlamentarier_id = @parlamentarierId ";

         using (MySqlCommand command = new MySqlCommand(cmd, conn))
         {
            command.Parameters.AddWithValue("@parlamentarierId", parlamentarierId);

            MySqlDataReader reader = command.ExecuteReader();

            DataTable dt = new DataTable();

            dt.Load(reader);

            if (dt.Rows.Count > 0)
            {
               string s = dt.Rows[0]["anzahl"].ToString();
               if (!string.IsNullOrEmpty(s))
               {
                  Int32.TryParse(s, out relations_total);
               }
            }
         }

         cmd = "select count(*) as anzahl " +
               "from zutrittsberechtigung zb " +
               "inner join mandat ma on ma.zutrittsberechtigung_id = zb.id " +
               "where zb.parlamentarier_id = @parlamentarierId " +
               "and art in ('vorstand', 'geschaeftsfuehrend')";

         using (MySqlCommand command = new MySqlCommand(cmd, conn))
         {
            command.Parameters.AddWithValue("@parlamentarierId", parlamentarierId);

            MySqlDataReader reader = command.ExecuteReader();

            DataTable dt = new DataTable();

            dt.Load(reader);

            string s = dt.Rows[0]["anzahl"].ToString();
            if (!string.IsNullOrEmpty(s))
            {
               Int32.TryParse(s, out relations_executive);
            }
         }

         relations_simple = relations_total - relations_executive;

         xmlData.Append("<relations_gast>" + relations_total + "</relations_gast>");
         xmlData.Append("<relations_gast_simple>" + relations_simple + "</relations_gast_simple>");
         xmlData.Append("<relations_gast_executive>" + relations_executive + "</relations_gast_executive>");

         return xmlData.ToString();
      }

      protected DataTable GetParlamentarierOnParteiData(string partei)
      {
         DataTable dtRet = null;

         try
         {
            using (MySqlConnection conn = new MySqlConnection(_connectString))
            {
               conn.Open();

               string cmd = "";

               /*
               select p.nachname as name, p.vorname as firstname, p.id as id
               from parlamentarier p
               inner join partei pa on pa.id = p.partei_id
               where p.ratstyp = 'NR' and pa.abkuerzung = 'SVP'
                */

               //
               // read Parlamentarier zu partei
               //
               cmd = "select p.nachname as name, p.vorname as firstname, p.id as id, p.ratstyp as ratstyp " +
                     "from parlamentarier p " +
                     "inner join partei pa on pa.id = p.partei_id " +
                     "where pa.abkuerzung = @partei " +
                     "and (@filterKommission = 0 or exists (select * from in_kommission ik where ik.parlamentarier_id = p.id and ik.kommission_id = @filterKommission)) " +
                     "and concat(p.vorname, ' ', p.nachname) like @filterPerson " +
                     "order by p.nachname, p.vorname";

               using (MySqlCommand command = new MySqlCommand(cmd, conn))
               {
                  command.Parameters.AddWithValue("@partei", partei);
                  command.Parameters.AddWithValue("@filterKommission", _filterKommission);
                  command.Parameters.AddWithValue("@filterBranche", _filterBranche);
                  command.Parameters.AddWithValue("@filterPerson", _filterPerson);
                  MySqlDataReader reader = command.ExecuteReader();
                  dtRet = new DataTable();
                  dtRet.Load(reader);
               }
            }
         }
         catch (Exception e)
         {
            string msg = e.Message;
            Response.Write(msg);
            Response.End();
         }

         return dtRet;
      }

      protected DataTable GetParlamentarierRelationsCount(int parlamentarierId)
      {
         DataTable dtRet = null;

         try
         {
            using (MySqlConnection conn = new MySqlConnection(_connectString))
            {
               conn.Open();

               string cmd = "";

               //
               // read Parlamentarier interessenbindung data
               //
               cmd = "select sum(anzahlTotal) as anzahlTotal, sum(anzahlExecutive) as anzahlExecutive, sum(anzahlTotal)-sum(anzahlExecutive) as anzahlSimple " +
                     "from " +
                     "( " +
                     "select count(*) as anzahlTotal, 0 as anzahlExecutive " +
                     "from interessenbindung ib " +
                     "where ib.parlamentarier_id = @parlamentarierId " +
                     "and (@filterBranche=0 or exists (select * from organisation o where o.id = ib.organisation_id and o.branche_id=@filterBranche )) " +
                     "and exists (select * from organisation o where o.id = ib.organisation_id and o.name_de like @filterOrganisation) " +
                     "union " +
                     "select 0 as anzahlTotal, count(*) as anzahlExecutive " +
                     "from interessenbindung ib " +
                     "where ib.parlamentarier_id = @parlamentarierId " +
                     "and (@filterBranche=0 or exists (select * from organisation o where o.id = ib.organisation_id and o.branche_id=@filterBranche )) " +
                     "and exists (select * from organisation o where o.id = ib.organisation_id and o.name_de like @filterOrganisation) " +
                     "and ib.art in ('vorstand', 'geschaeftsfuehrend') " +
                     ") t ";

               using (MySqlCommand command = new MySqlCommand(cmd, conn))
               {
                  command.Parameters.AddWithValue("@parlamentarierId", parlamentarierId);
                  command.Parameters.AddWithValue("@filterBranche", _filterBranche);
                  command.Parameters.AddWithValue("@filterOrganisation", _filterOrganisation);

                  MySqlDataReader reader = command.ExecuteReader();
                  dtRet = new DataTable();
                  dtRet.Load(reader);
               }
            }
         }
         catch (Exception e)
         {
            string msg = e.Message;
            Response.Write(msg);
            Response.End();
         }

         return dtRet;
      }

      protected DataTable GetParlamentarierGastRelationsCount(int parlamentarierId)
      {
         DataTable dtRet = null;

         try
         {
            using (MySqlConnection conn = new MySqlConnection(_connectString))
            {
               conn.Open();

               string cmd = "";

               //
               // read Parlamentarier interessenbindung data
               //
               cmd = "select sum(anzahlTotal) as anzahlTotal, sum(anzahlExecutive) as anzahlExecutive, sum(anzahlTotal)-sum(anzahlExecutive) as anzahlSimple " +
                     "from " +
                     "( " +
                     "select count(*) as anzahlTotal, 0 as anzahlExecutive " +
                     "from zutrittsberechtigung zb " +
                     "inner join mandat ma on ma.zutrittsberechtigung_id = zb.id " +
                     "where zb.parlamentarier_id = @parlamentarierId " +
                     "and (@filterBranche=0 or exists (select * from organisation o where o.id = ma.organisation_id and o.branche_id=@filterBranche )) " +
                     "and exists (select * from organisation o where o.id = ma.organisation_id and o.name_de like @filterOrganisation) " +
                     "union " +
                     "select 0 as anzahlTotal, count(*) as anzahlExecutive " +
                     "from zutrittsberechtigung zb " +
                     "inner join mandat ma on ma.zutrittsberechtigung_id = zb.id " +
                     "where zb.parlamentarier_id = @parlamentarierId " +
                     "and (@filterBranche=0 or exists (select * from organisation o where o.id = ma.organisation_id and o.branche_id=@filterBranche )) " +
                     "and exists (select * from organisation o where o.id = ma.organisation_id and o.name_de like @filterOrganisation) " +
                     "and ma.art in ('vorstand', 'geschaeftsfuehrend') " +
                     ") t ";

               using (MySqlCommand command = new MySqlCommand(cmd, conn))
               {
                  command.Parameters.AddWithValue("@parlamentarierId", parlamentarierId);
                  command.Parameters.AddWithValue("@filterBranche", _filterBranche);
                  command.Parameters.AddWithValue("@filterOrganisation", _filterOrganisation);

                  MySqlDataReader reader = command.ExecuteReader();
                  dtRet = new DataTable();
                  dtRet.Load(reader);
               }
            }
         }
         catch (Exception e)
         {
            string msg = e.Message;
            Response.Write(msg);
            Response.End();
         }

         return dtRet;
      }

      protected DataTable GetOrganisationData(int organisationId)
      {
         DataTable dtRet = null;

         /*
         select o.id, o.name_de, o.typ, o.homepage, b.id as branche_id, b.name as branche_name,
         ig1.id as interessengruppe1_id, ig1.name as interessengruppe1_name,
         ig2.id as interessengruppe2_id, ig2.name as interessengruppe2_name,
         ig3.id as interessengruppe3_id, ig3.name as interessengruppe3_name
         from organisation o
         left join branche b on b.id = o.branche_id
         left join interessengruppe ig1 on ig1.id = o.interessengruppe_id
         left join interessengruppe ig2 on ig2.id = o.interessengruppe2_id
         left join interessengruppe ig3 on ig3.id = o.interessengruppe3_id
         where 1 = 1 
         and o.id in (2, 10, 364, 367, 411)
         order by o.name_de
         */

         try
         {
            using (MySqlConnection conn = new MySqlConnection(_connectString))
            {
               conn.Open();

               string cmd = "";

               //
               // read Parlamentarier basis data
               //
               cmd = "select o.id, o.name_de, o.typ, o.homepage, b.id as branche_id, b.name as branche_name, " +
                     "ig1.id as interessengruppe1_id, ig1.name as interessengruppe1_name, " +
                     "ig2.id as interessengruppe2_id, ig2.name as interessengruppe2_name, " +
                     "ig3.id as interessengruppe3_id, ig3.name as interessengruppe3_name " +
                     "from organisation o " +
                     "left join branche b on b.id = o.branche_id " +
                     "left join interessengruppe ig1 on ig1.id = o.interessengruppe_id " +
                     "left join interessengruppe ig2 on ig2.id = o.interessengruppe2_id " +
                     "left join interessengruppe ig3 on ig3.id = o.interessengruppe3_id " +
                     "where 1 = 1 " +
                     "and o.id = @organisationId " +
                     "order by o.name_de ";

               using (MySqlCommand command = new MySqlCommand(cmd, conn))
               {
                  command.Parameters.AddWithValue("@organisationId", organisationId);
                  MySqlDataReader reader = command.ExecuteReader();
                  dtRet = new DataTable();
                  dtRet.Load(reader);
               }
            }
         }
         catch (Exception e)
         {
            string msg = e.Message;
            Response.Write(msg);
            Response.End();
         }

         return dtRet;
      }

      protected DataTable GetParlamentarierListOnOrganisation(int organisationId)
      {
         DataTable dtRet = null;

         /*
         select
         p.nachname as nachname, p.vorname as vorname, p.ratstyp as ratstyp, p.kanton as kanton, 
         pa.abkuerzung as partei, f.abkuerzung as fraktion, p.fraktionsfunktion as fraktionsfunktion, 
         p.im_rat_seit as im_rat_seit, p.beruf as beruf, p.geschlecht as geschlecht, p.geburtstag as geburtstag,
         ib.art as interessenbindung_art, ib.status as interessenbindung_status
         from parlamentarier p
         inner join interessenbindung ib on ib.parlamentarier_id = p.id
         left join partei pa on p.partei_id = pa.id 
         left join fraktion f on p.fraktion_id = f.id 
         where ib.organisation_id in (2, 10, 364, 367, 411)
         */

         try
         {
            using (MySqlConnection conn = new MySqlConnection(_connectString))
            {
               conn.Open();

               string cmd = "";

               //
               // read Parlamentarier basis data
               //
               cmd = "select " +
                     "p.id as parlamentarier_id, p.nachname as nachname, p.vorname as vorname, p.ratstyp as ratstyp, p.kanton as kanton, " +
                     "pa.abkuerzung as partei, f.abkuerzung as fraktion, p.fraktionsfunktion as fraktionsfunktion, " +
                     "p.im_rat_seit as im_rat_seit, p.beruf as beruf, p.geschlecht as geschlecht, p.geburtstag as geburtstag, " +
                     "ib.art as interessenbindung_art, ib.status as interessenbindung_status " +
                     "from parlamentarier p " +
                     "inner join interessenbindung ib on ib.parlamentarier_id = p.id " +
                     "left join partei pa on p.partei_id = pa.id " +
                     "left join fraktion f on p.fraktion_id = f.id " +
                     "where ib.organisation_id = @organisationId";

               using (MySqlCommand command = new MySqlCommand(cmd, conn))
               {
                  command.Parameters.AddWithValue("@organisationId", organisationId);
                  MySqlDataReader reader = command.ExecuteReader();
                  dtRet = new DataTable();
                  dtRet.Load(reader);
               }
            }
         }
         catch (Exception e)
         {
            string msg = e.Message;
            Response.Write(msg);
            Response.End();
         }

         return dtRet;
      }

      protected DataTable GetKommissionListOnParlamentarier(int parlamentarierId)
      {
         DataTable dtRet = null;

         /*
         select k.id, k.name, k.abkuerzung, k.typ 
         from kommission k
         inner join in_kommission ik on ik.kommission_id = k.id
         inner join parlamentarier p on p.id = ik.parlamentarier_id
         where p.id = 2
         */

         try
         {
            using (MySqlConnection conn = new MySqlConnection(_connectString))
            {
               conn.Open();

               string cmd = "";

               //
               // read Kommissionen on parlamentarierId
               //
               cmd = "select k.id, k.name, k.abkuerzung, k.typ, k.sachbereiche " +
                     "from kommission k " +
                     "inner join in_kommission ik on ik.kommission_id = k.id " +
                     "inner join parlamentarier p on p.id = ik.parlamentarier_id " +
                     "where p.id = @parlamentarierId";

               using (MySqlCommand command = new MySqlCommand(cmd, conn))
               {
                  command.Parameters.AddWithValue("@parlamentarierId", parlamentarierId);
                  MySqlDataReader reader = command.ExecuteReader();
                  dtRet = new DataTable();
                  dtRet.Load(reader);
               }
            }
         }
         catch (Exception e)
         {
            string msg = e.Message;
            Response.Write(msg);
            Response.End();
         }

         return dtRet;
      }

      protected DataTable GetMandatListOnOrganisation(int organisationId)
      {
         DataTable dtRet = null;

         /*
         select ma.art as mandat_art, 
         zb.id as zutrittsberechtigung_id, 
         zb.nachname as zutrittsberechtigung_nachname, zb.vorname as zutrittsberechtigung_vorname, zb.funktion as zutrittsberechtigung_funktion, zb.beruf as zutrittsberechtigung_beruf, 
         p.id as parlamentarier_id,
         p.nachname as parlamentarier_nachname, p.vorname as parlamentarier_vorname, p.ratstyp as ratstyp, p.kanton as parlamentarier_kanton, 
         pa.abkuerzung as partei, f.abkuerzung as fraktion, p.fraktionsfunktion as fraktionsfunktion, 
         p.im_rat_seit as im_rat_seit, p.beruf as parlamentarier_beruf, p.geschlecht as parlamentarier_geschlecht, p.geburtstag as parlamentarier_geburtstag
         from mandat ma
         inner join zutrittsberechtigung zb on zb.id = ma.zutrittsberechtigung_id
         inner join parlamentarier p on p.id = zb.parlamentarier_id
         left join partei pa on p.partei_id = pa.id
         left join fraktion f on p.fraktion_id = f.id
         where ma.organisation_id = 19
         */

         try
         {
            using (MySqlConnection conn = new MySqlConnection(_connectString))
            {
               conn.Open();

               string cmd = "";

               //
               // read Parlamentarier basis data
               //
               cmd = "select ma.art as mandat_art, " +
                     "zb.id as zutrittsberechtigung_id, " +
                     "zb.nachname as zutrittsberechtigung_nachname, zb.vorname as zutrittsberechtigung_vorname, zb.funktion as zutrittsberechtigung_funktion, zb.beruf as zutrittsberechtigung_beruf, " +
                     "p.id as parlamentarier_id, " +
                     "p.nachname as parlamentarier_nachname, p.vorname as parlamentarier_vorname, p.ratstyp as ratstyp, p.kanton as parlamentarier_kanton,  " +
                     "pa.abkuerzung as partei, f.abkuerzung as fraktion, p.fraktionsfunktion as fraktionsfunktion, " +
                     "p.im_rat_seit as im_rat_seit, p.beruf as parlamentarier_beruf, p.geschlecht as parlamentarier_geschlecht, p.geburtstag as parlamentarier_geburtstag " +
                     "from mandat ma " +
                     "inner join zutrittsberechtigung zb on zb.id = ma.zutrittsberechtigung_id " +
                     "inner join parlamentarier p on p.id = zb.parlamentarier_id " +
                     "left join partei pa on p.partei_id = pa.id " +
                     "left join fraktion f on p.fraktion_id = f.id " +
                     "where ma.organisation_id = @organisationId";

               using (MySqlCommand command = new MySqlCommand(cmd, conn))
               {
                  command.Parameters.AddWithValue("@organisationId", organisationId);
                  MySqlDataReader reader = command.ExecuteReader();
                  dtRet = new DataTable();
                  dtRet.Load(reader);
               }
            }
         }
         catch (Exception e)
         {
            string msg = e.Message;
            Response.Write(msg);
            Response.End();
         }

         return dtRet;
      }

      protected DataTable GetParlamentarierData(int parlamentarierId)
      {
         DataTable dtRet = null;

         try
         {
            using (MySqlConnection conn = new MySqlConnection(_connectString))
            {
               conn.Open();

               string cmd = "";

               //
               // read Parlamentarier basis data
               //
               cmd = "select " +
                     "p.nachname as nachname, p.vorname as vorname, p.ratstyp as ratstyp, p.kanton as kanton, " +
                     "pa.abkuerzung as partei, f.abkuerzung as fraktion, p.fraktionsfunktion as fraktionsfunktion, " +
                     "p.im_rat_seit as im_rat_seit, p.beruf as beruf, p.geschlecht as geschlecht, p.geburtstag as geburtstag " +
                     "from parlamentarier p " +
                     "left join partei pa on p.partei_id = pa.id " +
                     "left join fraktion f on p.fraktion_id = f.id " +
                     "where p.id = @parlamentarierId ";

               using (MySqlCommand command = new MySqlCommand(cmd, conn))
               {
                  command.Parameters.AddWithValue("@parlamentarierId", parlamentarierId);
                  MySqlDataReader reader = command.ExecuteReader();
                  dtRet = new DataTable();
                  dtRet.Load(reader);
               }
            }
         }
         catch (Exception e)
         {
            string msg = e.Message;
            Response.Write(msg);
            Response.End();
         }

         return dtRet;
      }

      protected DataTable GetInteressenbindungListOnParlamentarier(int parlamentarierId)
      {
         DataTable dtRet = null;

         try
         {
            using (MySqlConnection conn = new MySqlConnection(_connectString))
            {
               conn.Open();

               string cmd = "";

               //
               // read Parlamentarier Interessenbindungen
               //
               cmd = "select ib.art as art, ib.status as status, ib.organisation_id as organisation_id, o.name_de as organisation, o.typ as typ, b.name as branche " +
                     "from parlamentarier p " +
                     "inner join interessenbindung ib on p.id = ib.parlamentarier_id " +
                     "inner join organisation o on ib.organisation_id = o.id " +
                     "left join branche b on o.branche_id = b.id " +
                     "where p.id = @parlamentarierId " +
                     "and (@filterBranche=0 or o.branche_id=@filterBranche ) " +
                     "and o.name_de like @filterOrganisation " +
                     "and ((@optionFunction1=1 and ib.art not in ('vorstand', 'geschaeftsfuehrend')) or (@optionFunction2=1 and ib.art in ('vorstand', 'geschaeftsfuehrend'))) ";

               using (MySqlCommand command = new MySqlCommand(cmd, conn))
               {
                  command.Parameters.AddWithValue("@parlamentarierId", parlamentarierId);
                  command.Parameters.AddWithValue("@filterBranche", _filterBranche);
                  command.Parameters.AddWithValue("@optionFunction1", _optionFunction1);
                  command.Parameters.AddWithValue("@optionFunction2", _optionFunction2);
                  command.Parameters.AddWithValue("@filterOrganisation", _filterOrganisation);

                  MySqlDataReader reader = command.ExecuteReader();
                  dtRet = new DataTable();
                  dtRet.Load(reader);
               }
            }
         }
         catch (Exception e)
         {
            string msg = e.Message;
            Response.Write(msg);
            Response.End();
         }

         return dtRet;
      }

      protected DataTable GetZutrittsberechtigungListOnParlamentarier(int parlamentarierId)
      {
         DataTable dtRet = null;

         try
         {
            using (MySqlConnection conn = new MySqlConnection(_connectString))
            {
               conn.Open();

               string cmd = "";

               //
               // read Parlamentarier ZutrittsberechtigungList
               //
               cmd = "select zb.id as zutrittsberechtigung_id, zb.nachname as nachname, zb.vorname as vorname, zb.funktion as funktion, zb.beruf as beruf " +
                     "from parlamentarier p " +
                     "inner join zutrittsberechtigung zb on p.id = zb.parlamentarier_id " +
                     "where p.id = @parlamentarierId ";

               using (MySqlCommand command = new MySqlCommand(cmd, conn))
               {
                  command.Parameters.AddWithValue("@parlamentarierId", parlamentarierId);
                  MySqlDataReader reader = command.ExecuteReader();
                  dtRet = new DataTable();
                  dtRet.Load(reader);
               }
            }
         }
         catch (Exception e)
         {
            string msg = e.Message;
            Response.Write(msg);
            Response.End();
         }

         return dtRet;
      }

      protected DataTable GetMandatListOnZutrittsberechtigung(int zutrittsberechtigungId)
      {
         DataTable dtRet = null;

         try
         {
            using (MySqlConnection conn = new MySqlConnection(_connectString))
            {
               conn.Open();

               string cmd = "";

               //
               // read Zutrittsberechtigung MandateList
               //
               cmd = "select m.art as art, o.id as organisation_id, o.name_de as organisation, o.typ as typ, b.name as branche " +
                     "from parlamentarier p " +
                     "inner join zutrittsberechtigung zb on p.id = zb.parlamentarier_id " +
                     "inner join mandat m on zb.id = m.zutrittsberechtigung_id " +
                     "inner join organisation o on m.organisation_id = o.id " +
                     "left join branche b on o.branche_id = b.id " +
                     "where zb.id = @zutrittsberechtigungId " +
                     "and (@filterBranche=0 or o.branche_id=@filterBranche ) " +
                     "and o.name_de like @filterOrganisation " +
                     "and ((@optionFunction1=1 and m.art not in ('vorstand', 'geschaeftsfuehrend')) or (@optionFunction2=1 and m.art in ('vorstand', 'geschaeftsfuehrend'))) ";


               using (MySqlCommand command = new MySqlCommand(cmd, conn))
               {
                  command.Parameters.AddWithValue("@zutrittsberechtigungId", zutrittsberechtigungId);
                  command.Parameters.AddWithValue("@filterBranche", _filterBranche);
                  command.Parameters.AddWithValue("@optionFunction1", _optionFunction1);
                  command.Parameters.AddWithValue("@optionFunction2", _optionFunction2);
                  command.Parameters.AddWithValue("@filterOrganisation", _filterOrganisation);

                  MySqlDataReader reader = command.ExecuteReader();
                  dtRet = new DataTable();
                  dtRet.Load(reader);
               }
            }
         }
         catch (Exception e)
         {
            string msg = e.Message;
            Response.Write(msg);
            Response.End();
         }

         return dtRet;
      }

      public string GetOrganisationNetworkDataXML(int organisationId)
      {
         string ret = "";

         try
         {
            StringBuilder data = new StringBuilder();

            data.Append("<?xml version=\"1.0\" encoding=\"UTF-8\" ?>");

            data.Append("<organisation>");

            DataTable dtOrganisation = GetOrganisationData(organisationId);

            if (dtOrganisation != null && dtOrganisation.Rows.Count == 1)
            {
               DataRow rowOrganisation = dtOrganisation.Rows[0];

               data.Append("<id>" + rowOrganisation["id"].ToString() + "</id>");
               data.Append("<name_de>" + WebUtility.HtmlEncode(rowOrganisation["name_de"].ToString()) + "</name_de>");
               data.Append("<typ>" + WebUtility.HtmlEncode(rowOrganisation["typ"].ToString()) + "</typ>");
               data.Append("<url>" + WebUtility.HtmlEncode(rowOrganisation["homepage"].ToString()) + "</url>");
               data.Append("<branche_id>" + rowOrganisation["branche_id"].ToString() + "</branche_id>");
               data.Append("<branche>" + WebUtility.HtmlEncode(rowOrganisation["branche_name"].ToString()) + "</branche>");

               data.Append("<interessengruppe_list>");
               if (!string.IsNullOrEmpty(rowOrganisation["interessengruppe1_name"].ToString()))
               {
                  data.Append("<interessengruppe>");
                  data.Append("<id>" + rowOrganisation["interessengruppe1_id"].ToString() + "</id>");
                  data.Append("<name>" + WebUtility.HtmlEncode(rowOrganisation["interessengruppe1_name"].ToString()) + "</name>");
                  data.Append("</interessengruppe>");
               }
               if (!string.IsNullOrEmpty(rowOrganisation["interessengruppe2_name"].ToString()))
               {
                  data.Append("<interessengruppe>");
                  data.Append("<id>" + rowOrganisation["interessengruppe2_id"].ToString() + "</id>");
                  data.Append("<name>" + WebUtility.HtmlEncode(rowOrganisation["interessengruppe2_name"].ToString()) + "</name>");
                  data.Append("</interessengruppe>");
               }
               if (!string.IsNullOrEmpty(rowOrganisation["interessengruppe3_name"].ToString()))
               {
                  data.Append("<interessengruppe>");
                  data.Append("<id>" + rowOrganisation["interessengruppe3_id"].ToString() + "</id>");
                  data.Append("<name>" + WebUtility.HtmlEncode(rowOrganisation["interessengruppe3_name"].ToString()) + "</name>");
                  data.Append("</interessengruppe>");
               }
               data.Append("</interessengruppe_list>");

               // read parlamentarier list
               data.Append("<parlamentarier_list>");
               DataTable dtParlamentarier = GetParlamentarierListOnOrganisation(organisationId);
               if (dtParlamentarier != null && dtParlamentarier.Rows.Count > 0)
               {
                  foreach (DataRow rowParlamentarier in dtParlamentarier.Rows)
                  {
                     data.Append("<parlamentarier>");

                     int parlamentarier_id = 0;
                     Int32.TryParse(rowParlamentarier["parlamentarier_id"].ToString(), out parlamentarier_id);

                     data.Append("<interessenbindung_art>" + WebUtility.HtmlEncode(rowParlamentarier["interessenbindung_art"].ToString()) + "</interessenbindung_art>");
                     data.Append("<interessenbindung_status>" + WebUtility.HtmlEncode(rowParlamentarier["interessenbindung_status"].ToString()) + "</interessenbindung_status>");
                     data.Append("<id>" + rowParlamentarier["parlamentarier_id"].ToString() + "</id>");
                     data.Append("<nachname>" + WebUtility.HtmlEncode(rowParlamentarier["nachname"].ToString()) + "</nachname>");
                     data.Append("<vorname>" + WebUtility.HtmlEncode(rowParlamentarier["vorname"].ToString()) + "</vorname>");
                     data.Append("<ratstyp>" + WebUtility.HtmlEncode(rowParlamentarier["ratstyp"].ToString()) + "</ratstyp>");
                     data.Append("<kanton>" + WebUtility.HtmlEncode(rowParlamentarier["kanton"].ToString()) + "</kanton>");
                     data.Append("<partei>" + WebUtility.HtmlEncode(rowParlamentarier["partei"].ToString()) + "</partei>");
                     data.Append("<fraktion>" + WebUtility.HtmlEncode(rowParlamentarier["fraktion"].ToString()) + "</fraktion>");
                     data.Append("<fraktionsfunktion>" + WebUtility.HtmlEncode(rowParlamentarier["fraktionsfunktion"].ToString()) + "</fraktionsfunktion>");
                     data.Append("<im_rat_seit>" + rowParlamentarier["im_rat_seit"].ToString() + "</im_rat_seit>");
                     data.Append("<beruf>" + WebUtility.HtmlEncode(rowParlamentarier["beruf"].ToString()) + "</beruf>");
                     data.Append("<geschlecht>" + WebUtility.HtmlEncode(rowParlamentarier["geschlecht"].ToString()) + "</geschlecht>");
                     data.Append("<geburtstag>" + rowParlamentarier["geburtstag"].ToString() + "</geburtstag>");

                     data.Append("<kommission_list>");
                     DataTable dtKommission = GetKommissionListOnParlamentarier(parlamentarier_id);
                     if (dtKommission != null && dtKommission.Rows.Count > 0)
                     {
                        foreach (DataRow rowKommission in dtKommission.Rows)
                        {
                           data.Append("<kommission>");

                           data.Append("<id>" + rowKommission["id"].ToString() + "</id>");
                           data.Append("<name>" + WebUtility.HtmlEncode(rowKommission["name"].ToString()) + "</name>");
                           data.Append("<abkuerzung>" + WebUtility.HtmlEncode(rowKommission["abkuerzung"].ToString()) + "</abkuerzung>");
                           data.Append("<typ>" + WebUtility.HtmlEncode(rowKommission["typ"].ToString()) + "</typ>");

                           data.Append("</kommission>");
                        }
                     }
                     data.Append("</kommission_list>");

                     data.Append("</parlamentarier>");
                  }
               }
               data.Append("</parlamentarier_list>");

               // read zutrittsberechtigte list
               data.Append("<zutrittsberechtigte_list>");
               DataTable dtMandat = GetMandatListOnOrganisation(organisationId);
               if (dtMandat != null && dtMandat.Rows.Count > 0)
               {
                  foreach (DataRow rowMandat in dtMandat.Rows)
                  {
                     data.Append("<zutrittsberechtigte>");

                     int parlamentarier_id = 0;
                     Int32.TryParse(rowMandat["parlamentarier_id"].ToString(), out parlamentarier_id);

                     data.Append("<mandat_art>" + WebUtility.HtmlEncode(rowMandat["mandat_art"].ToString()) + "</mandat_art>");
                     data.Append("<id>" + rowMandat["zutrittsberechtigung_id"].ToString() + "</id>");
                     data.Append("<nachname>" + WebUtility.HtmlEncode(rowMandat["zutrittsberechtigung_nachname"].ToString()) + "</nachname>");
                     data.Append("<vorname>" + WebUtility.HtmlEncode(rowMandat["zutrittsberechtigung_vorname"].ToString()) + "</vorname>");
                     data.Append("<funktion>" + WebUtility.HtmlEncode(rowMandat["zutrittsberechtigung_funktion"].ToString()) + "</funktion>");
                     data.Append("<beruf>" + WebUtility.HtmlEncode(rowMandat["zutrittsberechtigung_beruf"].ToString()) + "</beruf>");

                     data.Append("<parlamentarier>");

                     data.Append("<id>" + rowMandat["parlamentarier_id"].ToString() + "</id>");
                     data.Append("<nachname>" + WebUtility.HtmlEncode(rowMandat["parlamentarier_nachname"].ToString()) + "</nachname>");
                     data.Append("<vorname>" + WebUtility.HtmlEncode(rowMandat["parlamentarier_vorname"].ToString()) + "</vorname>");
                     data.Append("<ratstyp>" + WebUtility.HtmlEncode(rowMandat["ratstyp"].ToString()) + "</ratstyp>");
                     data.Append("<kanton>" + WebUtility.HtmlEncode(rowMandat["parlamentarier_kanton"].ToString()) + "</kanton>");
                     data.Append("<partei>" + WebUtility.HtmlEncode(rowMandat["partei"].ToString()) + "</partei>");
                     data.Append("<fraktion>" + WebUtility.HtmlEncode(rowMandat["fraktion"].ToString()) + "</fraktion>");
                     data.Append("<fraktionsfunktion>" + WebUtility.HtmlEncode(rowMandat["fraktionsfunktion"].ToString()) + "</fraktionsfunktion>");
                     data.Append("<im_rat_seit>" + rowMandat["im_rat_seit"].ToString() + "</im_rat_seit>");
                     data.Append("<beruf>" + WebUtility.HtmlEncode(rowMandat["parlamentarier_beruf"].ToString()) + "</beruf>");
                     data.Append("<geschlecht>" + WebUtility.HtmlEncode(rowMandat["parlamentarier_geschlecht"].ToString()) + "</geschlecht>");
                     data.Append("<geburtstag>" + rowMandat["parlamentarier_geburtstag"].ToString() + "</geburtstag>");

                     data.Append("<kommission_list>");
                     DataTable dtKommission = GetKommissionListOnParlamentarier(parlamentarier_id);
                     if (dtKommission != null && dtKommission.Rows.Count > 0)
                     {
                        foreach (DataRow rowKommission in dtKommission.Rows)
                        {
                           data.Append("<kommission>");

                           data.Append("<id>" + rowKommission["id"].ToString() + "</id>");
                           data.Append("<name>" + WebUtility.HtmlEncode(rowKommission["name"].ToString()) + "</name>");
                           data.Append("<abkuerzung>" + WebUtility.HtmlEncode(rowKommission["abkuerzung"].ToString()) + "</abkuerzung>");
                           data.Append("<typ>" + WebUtility.HtmlEncode(rowKommission["typ"].ToString()) + "</typ>");

                           data.Append("</kommission>");
                        }
                     }
                     data.Append("</kommission_list>");

                     data.Append("</parlamentarier>");

                     data.Append("</zutrittsberechtigte>");
                  }
               }
               data.Append("</zutrittsberechtigte_list>");
            }

            data.Append("</organisation>");

            ret = data.ToString();
         }
         catch (Exception e)
         {
            string msg = e.Message;
            Response.Write(msg);
            Response.End();
         }

         return ret;


      }

      public string GetOrganisationNetworkDataJSON(int organisationId)
      {
         string ret = "";

         try
         {
            StringBuilder data = new StringBuilder();

            data.Append("{");

            DataTable dtOrganisation = GetOrganisationData(organisationId);

            if (dtOrganisation != null && dtOrganisation.Rows.Count == 1)
            {
               DataRow rowOrganisation = dtOrganisation.Rows[0];

               data.Append("\"id\":\"" + rowOrganisation["id"].ToString() + "\",");
               data.Append("\"name_de\":\"" + ReplaceQuotationMark(rowOrganisation["name_de"].ToString()) + "\",");
               data.Append("\"typ\":\"" + rowOrganisation["typ"].ToString() + "\",");
               data.Append("\"url\":\"" + rowOrganisation["homepage"].ToString() + "\",");
               data.Append("\"branche_id\":\"" + rowOrganisation["branche_id"].ToString() + "\",");
               data.Append("\"branche\":\"" + rowOrganisation["branche_name"].ToString() + "\",");

               data.Append("\"interessengruppe_list\":[");  // start tag interessengruppe_list
               if (!string.IsNullOrEmpty(rowOrganisation["interessengruppe1_name"].ToString()))
               {
                  data.Append("{");  // start tag interessengruppe
                  data.Append("\"id\":\"" + rowOrganisation["interessengruppe1_id"].ToString() + "\",");
                  data.Append("\"name\":\"" + rowOrganisation["interessengruppe1_name"].ToString() + "\"");
                  data.Append("},"); // end tag interessengruppe
               }
               if (!string.IsNullOrEmpty(rowOrganisation["interessengruppe2_name"].ToString()))
               {
                  data.Append("{");  // start tag interessengruppe
                  data.Append("\"id\":\"" + rowOrganisation["interessengruppe2_id"].ToString() + "\",");
                  data.Append("\"name\":\"" + rowOrganisation["interessengruppe2_name"].ToString() + "\"");
                  data.Append("},"); // end tag interessengruppe
               }
               if (!string.IsNullOrEmpty(rowOrganisation["interessengruppe3_name"].ToString()))
               {
                  data.Append("{");  // start tag interessengruppe
                  data.Append("\"id\":\"" + rowOrganisation["interessengruppe3_id"].ToString() + "\",");
                  data.Append("\"name\":\"" + rowOrganisation["interessengruppe3_name"].ToString() + "\"");
                  data.Append("},"); // end tag interessengruppe
               }
               // remove trailing ','
               if (data.ToString().EndsWith(",")) data = data.Remove(data.Length - 1, 1);
               data.Append("],");  // end tag interessengruppe_list

               // read parlamentarier list
               data.Append("\"parlamentarier_list\":[");  // start tag parlamentarier_list
               DataTable dtParlamentarier = GetParlamentarierListOnOrganisation(organisationId);
               if (dtParlamentarier != null && dtParlamentarier.Rows.Count > 0)
               {
                  foreach (DataRow rowParlamentarier in dtParlamentarier.Rows)
                  {
                     data.Append("{"); // start tag parlamentarier

                     int parlamentarier_id = 0;
                     Int32.TryParse(rowParlamentarier["parlamentarier_id"].ToString(), out parlamentarier_id);

                     data.Append("\"interessenbindung_art\":\"" + rowParlamentarier["interessenbindung_art"].ToString() + "\",");
                     data.Append("\"interessenbindung_status\":\"" + rowParlamentarier["interessenbindung_status"].ToString() + "\",");
                     data.Append("\"id\":\"" + rowParlamentarier["parlamentarier_id"].ToString() + "\",");
                     data.Append("\"nachname\":\"" + rowParlamentarier["nachname"].ToString() + "\",");
                     data.Append("\"vorname\":\"" + rowParlamentarier["vorname"].ToString() + "\",");
                     data.Append("\"ratstyp\":\"" + rowParlamentarier["ratstyp"].ToString() + "\",");
                     data.Append("\"kanton\":\"" + rowParlamentarier["kanton"].ToString() + "\",");
                     data.Append("\"partei\":\"" + rowParlamentarier["partei"].ToString() + "\",");
                     data.Append("\"fraktion\":\"" + rowParlamentarier["fraktion"].ToString() + "\",");
                     data.Append("\"fraktionsfunktion\":\"" + rowParlamentarier["fraktionsfunktion"].ToString() + "\",");
                     data.Append("\"im_rat_seit\":\"" + rowParlamentarier["im_rat_seit"].ToString() + "\",");
                     data.Append("\"beruf\":\"" + rowParlamentarier["beruf"].ToString() + "\",");
                     data.Append("\"geschlecht\":\"" + rowParlamentarier["geschlecht"].ToString() + "\",");
                     data.Append("\"geburtstag\":\"" + rowParlamentarier["geburtstag"].ToString() + "\",");

                     data.Append("\"kommission_list\":[");  // start tag kommission_list
                     DataTable dtKommission = GetKommissionListOnParlamentarier(parlamentarier_id);
                     if (dtKommission != null && dtKommission.Rows.Count > 0)
                     {
                        foreach (DataRow rowKommission in dtKommission.Rows)
                        {
                           data.Append("{");  // start tag kommission

                           data.Append("\"id\":\"" + rowKommission["id"].ToString() + "\",");
                           data.Append("\"name\":\"" + rowKommission["name"].ToString() + "\",");
                           data.Append("\"abkuerzung\":\"" + rowKommission["abkuerzung"].ToString() + "\",");
                           data.Append("\"typ\":\"" + rowKommission["typ"].ToString() + "\"");

                           data.Append("},"); // end tag kommission
                        }
                     }
                     // remove trailing ','
                     if (data.ToString().EndsWith(",")) data = data.Remove(data.Length - 1, 1);
                     data.Append("]");  // end tag kommission_list

                     data.Append("},");   // end tag parlamentarier
                  }
               }
               // remove trailing ','
               if (data.ToString().EndsWith(",")) data = data.Remove(data.Length - 1, 1);
               data.Append("],"); // end tag parlamentarier_list

               // read zutrittsberechtigte list
               data.Append("\"zutrittsberechtigte_list\":[");  // start tag zutrittsberechtigte_list
               DataTable dtMandat = GetMandatListOnOrganisation(organisationId);
               if (dtMandat != null && dtMandat.Rows.Count > 0)
               {
                  foreach (DataRow rowMandat in dtMandat.Rows)
                  {
                     data.Append("{");  // start tag zutrittsberechtigte

                     int parlamentarier_id = 0;
                     Int32.TryParse(rowMandat["parlamentarier_id"].ToString(), out parlamentarier_id);

                     data.Append("\"mandat_art\":\"" + rowMandat["mandat_art"].ToString() + "\",");
                     data.Append("\"id\":\"" + rowMandat["zutrittsberechtigung_id"].ToString() + "\",");
                     data.Append("\"nachname\":\"" + rowMandat["zutrittsberechtigung_nachname"].ToString() + "\",");
                     data.Append("\"vorname\":\"" + rowMandat["zutrittsberechtigung_vorname"].ToString() + "\",");
                     data.Append("\"funktion\":\"" + rowMandat["zutrittsberechtigung_funktion"].ToString() + "\",");
                     data.Append("\"beruf\":\"" + rowMandat["zutrittsberechtigung_beruf"].ToString() + "\",");

                     data.Append("\"parlamentarier\":{"); // start tag parlamentarier

                     data.Append("\"id\":\"" + rowMandat["parlamentarier_id"].ToString() + "\",");
                     data.Append("\"nachname\":\"" + rowMandat["parlamentarier_nachname"].ToString() + "\",");
                     data.Append("\"vorname\":\"" + rowMandat["parlamentarier_vorname"].ToString() + "\",");
                     data.Append("\"ratstyp\":\"" + rowMandat["ratstyp"].ToString() + "\",");
                     data.Append("\"kanton\":\"" + rowMandat["parlamentarier_kanton"].ToString() + "\",");
                     data.Append("\"partei\":\"" + rowMandat["partei"].ToString() + "\",");
                     data.Append("\"fraktion\":\"" + rowMandat["fraktion"].ToString() + "\",");
                     data.Append("\"fraktionsfunktion\":\"" + rowMandat["fraktionsfunktion"].ToString() + "\",");
                     data.Append("\"im_rat_seit\":\"" + rowMandat["im_rat_seit"].ToString() + "\",");
                     data.Append("\"beruf\":\"" + rowMandat["parlamentarier_beruf"].ToString() + "\",");
                     data.Append("\"geschlecht\":\"" + rowMandat["parlamentarier_geschlecht"].ToString() + "\",");
                     data.Append("\"geburtstag\":\"" + rowMandat["parlamentarier_geburtstag"].ToString() + "\",");

                     data.Append("\"kommission_list\":[");   // start tag kommission_list
                     DataTable dtKommission = GetKommissionListOnParlamentarier(parlamentarier_id);
                     if (dtKommission != null && dtKommission.Rows.Count > 0)
                     {
                        foreach (DataRow rowKommission in dtKommission.Rows)
                        {
                           data.Append("{");  // start tag kommission

                           data.Append("\"id\":\"" + rowKommission["id"].ToString() + "\",");
                           data.Append("\"name\":\"" + rowKommission["name"].ToString() + "\",");
                           data.Append("\"abkuerzung\":\"" + rowKommission["abkuerzung"].ToString() + "\",");
                           data.Append("\"typ\":\"" + rowKommission["typ"].ToString() + "\"");

                           data.Append("},"); // end tag kommission
                        }
                     }
                     // remove trailing ','
                     if (data.ToString().EndsWith(",")) data = data.Remove(data.Length - 1, 1);
                     data.Append("]");  // end tag kommission_list

                     data.Append("}");   // end tag parlamentarier

                     data.Append("},"); // end tag zutrittsberechtigte
                  }
               }
               // remove trailing ','
               if (data.ToString().EndsWith(",")) data = data.Remove(data.Length - 1, 1);
               data.Append("]"); // end tag zutrittsberechtigte_list
            }

            data.Append("}");

            ret = data.ToString();
         }
         catch (Exception e)
         {
            string msg = e.Message;
            Response.Write(msg);
            Response.End();
         }

         return ret;


      }

      public string GetParlamentarierNetworkDataXML(int parlamentarierId)
      {
         string ret = "";

         try
         {
            StringBuilder data = new StringBuilder();

            data.Append("<?xml version=\"1.0\" encoding=\"UTF-8\" ?>");

            data.Append("<parlamentarier>");

            //
            // read Parlamentarier basis data
            //
            DataTable dtParlamentarier = GetParlamentarierData(parlamentarierId);

            if (dtParlamentarier != null && dtParlamentarier.Rows.Count == 1)
            {
               DataRow rowParlamentarier = dtParlamentarier.Rows[0];

               data.Append("<id>" + parlamentarierId.ToString() + "</id>");
               data.Append("<nachname>" + WebUtility.HtmlEncode(rowParlamentarier["nachname"].ToString()) + "</nachname>");
               data.Append("<vorname>" + WebUtility.HtmlEncode(rowParlamentarier["vorname"].ToString()) + "</vorname>");
               data.Append("<ratstyp>" + WebUtility.HtmlEncode(rowParlamentarier["ratstyp"].ToString()) + "</ratstyp>");
               data.Append("<kanton>" + WebUtility.HtmlEncode(rowParlamentarier["kanton"].ToString()) + "</kanton>");
               data.Append("<partei>" + WebUtility.HtmlEncode(rowParlamentarier["partei"].ToString()) + "</partei>");
               data.Append("<fraktion>" + WebUtility.HtmlEncode(rowParlamentarier["fraktion"].ToString()) + "</fraktion>");
               data.Append("<fraktionsfunktion>" + WebUtility.HtmlEncode(rowParlamentarier["fraktionsfunktion"].ToString()) + "</fraktionsfunktion>");
               data.Append("<im_rat_seit>" + rowParlamentarier["im_rat_seit"].ToString() + "</im_rat_seit>");
               data.Append("<beruf>" + WebUtility.HtmlEncode(rowParlamentarier["beruf"].ToString()) + "</beruf>");
               data.Append("<geschlecht>" + WebUtility.HtmlEncode(rowParlamentarier["geschlecht"].ToString()) + "</geschlecht>");
               data.Append("<geburtstag>" + rowParlamentarier["geburtstag"].ToString() + "</geburtstag>");


               //
               // read Parlamentarier Kommissionen data
               //
               DataTable dtKommission = GetKommissionListOnParlamentarier(parlamentarierId);
               if (dtKommission != null && dtKommission.Rows.Count > 0)
               {
                  foreach (DataRow rowKommission in dtKommission.Rows)
                  {
                     data.Append("<kommission>");

                     data.Append("<id>" + rowKommission["id"].ToString() + "</id>");
                     data.Append("<name>" + WebUtility.HtmlEncode(rowKommission["name"].ToString()) + "</name>");
                     data.Append("<abkuerzung>" + rowKommission["abkuerzung"].ToString() + "</abkuerzung>");
                     data.Append("<typ>" + rowKommission["typ"].ToString() + "</typ>");

                     data.Append("</kommission>");
                  }
               }

               //
               // read Parlamentarier interessenbindung data
               //
               DataTable dtInteressenbindung = GetInteressenbindungListOnParlamentarier(parlamentarierId);
               if (dtInteressenbindung != null && dtInteressenbindung.Rows.Count > 0)
               {
                  foreach (DataRow rowInteressenbindung in dtInteressenbindung.Rows)
                  {
                     data.Append("<interessenbindung>");

                     data.Append("<art>" + WebUtility.HtmlEncode(rowInteressenbindung["art"].ToString()) + "</art>");
                     data.Append("<status>" + WebUtility.HtmlEncode(rowInteressenbindung["status"].ToString()) + "</status>");

                     data.Append("<organisation>");
                     data.Append("<id>" + rowInteressenbindung["organisation_id"].ToString() + "</id>");
                     data.Append("<name_de>" + WebUtility.HtmlEncode(rowInteressenbindung["organisation"].ToString()) + "</name_de>");
                     data.Append("<typ>" + WebUtility.HtmlEncode(rowInteressenbindung["typ"].ToString()) + "</typ>");
                     data.Append("<branche>" + WebUtility.HtmlEncode(rowInteressenbindung["branche"].ToString()) + "</branche>");

                     data.Append("</organisation>");

                     data.Append("</interessenbindung>");
                  }
               }

               //
               // read Parlamentarier Zutrittsberechtigung
               //
               DataTable dtZutrittsberechtigung = GetZutrittsberechtigungListOnParlamentarier(parlamentarierId);
               if (dtZutrittsberechtigung != null && dtZutrittsberechtigung.Rows.Count > 0)
               {
                  foreach (DataRow rowZutrittsberechtigung in dtZutrittsberechtigung.Rows)
                  {
                     data.Append("<zutrittsberechtigung>");

                     data.Append("<id>" + rowZutrittsberechtigung["zutrittsberechtigung_id"].ToString() + "</id>");
                     data.Append("<nachname>" + WebUtility.HtmlEncode(rowZutrittsberechtigung["nachname"].ToString()) + "</nachname>");
                     data.Append("<vorname>" + WebUtility.HtmlEncode(rowZutrittsberechtigung["vorname"].ToString()) + "</vorname>");
                     data.Append("<funktion>" + WebUtility.HtmlEncode(rowZutrittsberechtigung["funktion"].ToString()) + "</funktion>");
                     data.Append("<beruf>" + WebUtility.HtmlEncode(rowZutrittsberechtigung["beruf"].ToString()) + "</beruf>");

                     // read Mandate fuer Zutrittsberechtigung
                     int zutrittsberechtigungId = 0;
                     Int32.TryParse(rowZutrittsberechtigung["zutrittsberechtigung_id"].ToString(), out zutrittsberechtigungId);
                     DataTable dtMandat = GetMandatListOnZutrittsberechtigung(zutrittsberechtigungId);
                     if (dtMandat != null && dtMandat.Rows.Count > 0)
                     {
                        foreach (DataRow rowMandat in dtMandat.Rows)
                        {
                           data.Append("<mandat>");

                           data.Append("<art>" + rowMandat["art"].ToString() + "</art>");

                           data.Append("<organisation>");
                           data.Append("<id>" + rowMandat["organisation_id"].ToString() + "</id>");
                           data.Append("<name_de>" + WebUtility.HtmlEncode(rowMandat["organisation"].ToString()) + "</name_de>");
                           data.Append("<typ>" + WebUtility.HtmlEncode(rowMandat["typ"].ToString()) + "</typ>");
                           data.Append("<branche>" + WebUtility.HtmlEncode(rowMandat["branche"].ToString()) + "</branche>");

                           data.Append("</organisation>");

                           data.Append("</mandat>");
                        }
                     }

                     data.Append("</zutrittsberechtigung>");
                  }
               }


            }

            data.Append("</parlamentarier>");

            ret = data.ToString();
         }
         catch (Exception e)
         {
            string msg = e.Message;
            Response.Write(msg);
            Response.End();
         }

         return ret;
      }

      public string GetParlamentarierNetworkDataJSON(int parlamentarierId)
      {
         string ret = "";

         try
         {
            StringBuilder data = new StringBuilder();

            data.Append("{");
            data.Append("\"parlamentarier\": {");

            //
            // read Parlamentarier basis data
            //
            DataTable dtParlamentarier = GetParlamentarierData(parlamentarierId);

            if (dtParlamentarier != null && dtParlamentarier.Rows.Count == 1)
            {
               DataRow rowParlamentarier = dtParlamentarier.Rows[0];

               data.Append("\"id\":\"" + parlamentarierId.ToString() + "\",");
               data.Append("\"nachname\":\"" + rowParlamentarier["nachname"].ToString() + "\",");
               data.Append("\"vorname\":\"" + rowParlamentarier["vorname"].ToString() + "\",");
               data.Append("\"ratstyp\":\"" + rowParlamentarier["ratstyp"].ToString() + "\",");
               data.Append("\"kanton\":\"" + rowParlamentarier["kanton"].ToString() + "\",");
               data.Append("\"partei\":\"" + rowParlamentarier["partei"].ToString() + "\",");
               data.Append("\"fraktion\":\"" + rowParlamentarier["fraktion"].ToString() + "\",");
               data.Append("\"fraktionsfunktion\":\"" + rowParlamentarier["fraktionsfunktion"].ToString() + "\",");
               data.Append("\"im_rat_seit\":\"" + rowParlamentarier["im_rat_seit"].ToString() + "\",");
               data.Append("\"beruf\":\"" + rowParlamentarier["beruf"].ToString() + "\",");
               data.Append("\"geschlecht\":\"" + rowParlamentarier["geschlecht"].ToString() + "\",");
               data.Append("\"geburtstag\":\"" + rowParlamentarier["geburtstag"].ToString() + "\",");


               //
               // read Parlamentarier Kommissionen data
               //
               data.Append("\"kommission\":[");  // start tag kommission
               DataTable dtKommission = GetKommissionListOnParlamentarier(parlamentarierId);
               if (dtKommission != null && dtKommission.Rows.Count > 0)
               {
                  foreach (DataRow rowKommission in dtKommission.Rows)
                  {
                     data.Append("{");

                     data.Append("\"id\":\"" + rowKommission["id"].ToString() + "\",");
                     data.Append("\"name\":\"" + rowKommission["name"].ToString() + "\",");
                     data.Append("\"abkuerzung\":\"" + rowKommission["abkuerzung"].ToString() + "\",");
                     data.Append("\"typ\":\"" + rowKommission["typ"].ToString() + "\"");

                     data.Append("},");
                  }
               }
               // remove trailing ','
               if (data.ToString().EndsWith(",")) data = data.Remove(data.Length - 1, 1);
               data.Append("],");  // end tag kommission

               //
               // read Parlamentarier interessenbindung data
               //
               data.Append("\"interessenbindung\":[");  // start tag interessenbindung
               DataTable dtInteressenbindung = GetInteressenbindungListOnParlamentarier(parlamentarierId);
               if (_optionDeep1 == 1 && dtInteressenbindung != null && dtInteressenbindung.Rows.Count > 0)
               {
                  foreach (DataRow rowInteressenbindung in dtInteressenbindung.Rows)
                  {
                     data.Append("{");

                     data.Append("\"art\":\"" + rowInteressenbindung["art"].ToString() + "\",");
                     data.Append("\"status\":\"" + rowInteressenbindung["status"].ToString() + "\",");

                     data.Append("\"organisation\": {");
                     data.Append("\"id\":\"" + rowInteressenbindung["organisation_id"].ToString() + "\",");
                     data.Append("\"name_de\":\"" + ReplaceQuotationMark(rowInteressenbindung["organisation"].ToString()) + "\",");
                     data.Append("\"typ\":\"" + rowInteressenbindung["typ"].ToString() + "\",");
                     data.Append("\"branche\":\"" + rowInteressenbindung["branche"].ToString() + "\"");

                     data.Append("}");

                     data.Append("},");
                  }
               }
               // remove trailing ','
               if (data.ToString().EndsWith(",")) data = data.Remove(data.Length - 1, 1);
               data.Append("],");  // end tag interessenbindung

               //
               // read Parlamentarier Zutrittsberechtigung
               //
               data.Append("\"zutrittsberechtigung\":[");  // start tag zutrittsberechtigung
               DataTable dtZutrittsberechtigung = GetZutrittsberechtigungListOnParlamentarier(parlamentarierId);
               if (_optionDeep2 == 1 && dtZutrittsberechtigung != null && dtZutrittsberechtigung.Rows.Count > 0)
               {
                  foreach (DataRow rowZutrittsberechtigung in dtZutrittsberechtigung.Rows)
                  {
                     data.Append("{");

                     data.Append("\"id\":\"" + rowZutrittsberechtigung["zutrittsberechtigung_id"].ToString() + "\",");
                     data.Append("\"nachname\":\"" + rowZutrittsberechtigung["nachname"].ToString() + "\",");
                     data.Append("\"vorname\":\"" + rowZutrittsberechtigung["vorname"].ToString() + "\",");
                     data.Append("\"funktion\":\"" + rowZutrittsberechtigung["funktion"].ToString() + "\",");
                     data.Append("\"beruf\":\"" + rowZutrittsberechtigung["beruf"].ToString() + "\",");

                     // read Mandate fuer Zutrittsberechtigung
                     int zutrittsberechtigungId = 0;
                     Int32.TryParse(rowZutrittsberechtigung["zutrittsberechtigung_id"].ToString(), out zutrittsberechtigungId);
                     data.Append("\"mandat\":[");  // start tag mandat
                     DataTable dtMandat = GetMandatListOnZutrittsberechtigung(zutrittsberechtigungId);
                     if (dtMandat != null && dtMandat.Rows.Count > 0)
                     {
                        foreach (DataRow rowMandat in dtMandat.Rows)
                        {
                           data.Append("{");

                           data.Append("\"art\":\"" + rowMandat["art"].ToString() + "\",");

                           data.Append("\"organisation\": {");
                           data.Append("\"id\":\"" + rowMandat["organisation_id"].ToString() + "\",");
                           data.Append("\"name_de\":\"" + ReplaceQuotationMark(rowMandat["organisation"].ToString()) + "\",");
                           data.Append("\"typ\":\"" + rowMandat["typ"].ToString() + "\",");
                           data.Append("\"branche\":\"" + rowMandat["branche"].ToString() + "\"");

                           data.Append("}");

                           data.Append("},");
                        }
                     }
                     // remove trailing ','
                     if (data.ToString().EndsWith(",")) data = data.Remove(data.Length - 1, 1);
                     data.Append("]");  // end tag mandat

                     data.Append("},");
                  }
               }
               // remove trailing ','
               if (data.ToString().EndsWith(",")) data = data.Remove(data.Length - 1, 1);
               data.Append("],");  // end tag zutrittsberechtigung

            }

            // remove trailing ','
            if (data.ToString().EndsWith(",")) data = data.Remove(data.Length - 1, 1);
            data.Append("}");
            data.Append("}");

            ret = data.ToString();
         }
         catch (Exception e)
         {
            string msg = e.Message;
            Response.Write(msg);
            Response.End();
         }

         return ret;
      }
   }
}
