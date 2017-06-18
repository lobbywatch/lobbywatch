library(jsonlite)

#---------------------------------------------------------------------------------------------------------------------------
# Check whether each element of a vector is contained in a list: 

sapply(List, function(x) Vector %in% x)
mapply(function(x, y) {x %in% y}, list(Vector), List)
vapply(List, function(x) Vector %in% x, logical(length(Vector)))

# The above commands can be used to check for repetitions among the entries of a vector: 

v <- 1:16

sapply(v, function(x) v %in% x)
mapply(function(x, y) {x %in% y}, list(v), v)
vapply(v, function(x) v %in% x, logical(length(v)))

#---------------------------------------------------------------------------------------------------------------------------
#---------------------------------------------------------------------------------------------------------------------------


# Building blocks for the loop: 

LobbyData <- fromJSON(paste0("http://lobbywatch.ch/de/data/interface/v1/json/table/parlamentarier/aggregated/id/", 17))

colnames(LobbyData[["data"]][["interessenbindungen"]])


class(LobbyData[["data"]][["interessenbindungen"]]$organisation_id)

v <- LobbyData[["data"]][["interessenbindungen"]]$organisation_id

m <- vapply(v, function(x) v %in% x, logical(length(v)))

class(m)

names(m)

m[1,3]

dim(m)

grep("TRUE", m, value = FALSE) 

length(grep("TRUE", m, value = FALSE)) == dim(m)[1]

DoubleEntryControl_Vector <- integer()

if(length(grep("TRUE", m, value = FALSE)) == dim(m)[1]){
	append(DoubleEntryControl_Vector[i], 0)}else{
		append(DoubleEntryControl_Vector[i], 1)
		}
		

#---------------------------------------------------------------------------------------------------------------------------

# More elegant way to check for double entries: 
				

vec <- c(1,2,3,3,4,5,5,6)

Logic_Vec <- duplicated(vec)

Values_select <- vec[duplicated(vec)]

vec_select <- integer(length(Values_select)*2)

for(i in 1:length(Values_select)){
	vec_select[c((i*2)-1,i*2)] <- vec[vec==Values_select[i]]
}


#---------------------------------------------------------------------------------------------------------------------------

# More building blocks: 


duplicated(LobbyData[["data"]][["interessenbindungen"]]$organisation_id)




tempo_frame <- LobbyData[["data"]][["interessenbindungen"]][,c(43,44,54,55,56,57,58,59)]		

dupli_vector <- LobbyData[["data"]][["interessenbindungen"]]$organisation_id[duplicated(LobbyData[["data"]][["interessenbindungen"]]$organisation_id)]
dupli_vector

tempo_frame_dupli <- data.frame(matrix(0,2*length(dupli_vector),dim(tempo_frame)[2]))
colnames(tempo_frame_dupli) <- colnames(tempo_frame)
tempo_frame_dupli


for(i in 1:length(dupli_vector)){
	tempo_frame_dupli[c((i*2)-1,i*2),] <- tempo_frame[tempo_frame[,1]==dupli_vector[i],]
}	
	
#---------------------------------------------------------------------------------------------------------------------------



erroneous_duplications <- integer()


for(i in 1:length(dupli_vector)){
	if(tempo_frame_dupli[i*2,]$von_unix >= tempo_frame_dupli[c((i*2)-1),]$bis_unix){
	erroneous_duplications <- append(erroneous_duplications,0)
	}else{erroneous_duplications <- append(erroneous_duplications,1)
		
	}
}


#6  17  28  30  39  43  49  66  86 159 180 183 212 269 271 272 287 289 290 294 314


#---------------------------------------------------------------------------------------------------------------------------
#---------------------------------------------------------------------------------------------------------------------------
#---------------------------------------------------------------------------------------------------------------------------

# Automated checking for double entries in 'LobbyData[["data"]][["interessenbindungen"]]$organisation_id'
#---------------------------------------------------------------------------------------------------------------------------


# Looop through the data base to find double entries in 'LobbyData[["data"]][["interessenbindungen"]]$organisation_id': 
#---------------------------------------------------------------------------------------------------------------------------


rm(list=ls())

DoubleEntryControl_Vector <- integer()
for(i in 1:500){
	LobbyData <- fromJSON(paste0("http://lobbywatch.ch/de/data/interface/v1/json/table/parlamentarier/aggregated/id/", i))
	if(LobbyData[["count"]]==1 & is.null(LobbyData[["data"]][["partei_id"]])==FALSE){
		v <- LobbyData[["data"]][["interessenbindungen"]]$organisation_id
		m <- vapply(v, function(x) v %in% x, logical(length(v)))
		if(length(grep("TRUE", m, value = FALSE)) == dim(m)[1]){
	DoubleEntryControl_Vector <- append(DoubleEntryControl_Vector, 0)}else{
		DoubleEntryControl_Vector <- append(DoubleEntryControl_Vector, 1)
		}
			}else{DoubleEntryControl_Vector <- append(DoubleEntryControl_Vector, 0)
			}
}



Parla_ID_DoubleEntries <- grep(1, DoubleEntryControl_Vector, value = FALSE)


Parla_ID_DoubleEntries

#6  17  28  30  39  43  49  66  86 159 180 183 212 269 271 272 287 289 290 294 314

erroneous_duplications <- integer()

for(i in 1:length(Parla_ID_DoubleEntries)){
	LobbyData <- fromJSON(paste0("http://lobbywatch.ch/de/data/interface/v1/json/table/parlamentarier/aggregated/id/", Parla_ID_DoubleEntries[i]))
	tempo_frame <- LobbyData[["data"]][["interessenbindungen"]][,c(43,44,54,55,56,57,58,59)]		
	dupli_vector <- LobbyData[["data"]][["interessenbindungen"]]$organisation_id[duplicated(LobbyData[["data"]][["interessenbindungen"]]$organisation_id)]
	tempo_frame_dupli <- data.frame(matrix(0,2*length(dupli_vector),dim(tempo_frame)[2]))
	colnames(tempo_frame_dupli) <- colnames(tempo_frame)
		for(i in 1:length(dupli_vector)){
			tempo_frame_dupli[c((i*2)-1,i*2),] <- tempo_frame[tempo_frame$organisation_id==dupli_vector[i],]
		}	
		for(i in 1:length(dupli_vector)){
			if(is.na(tempo_frame_dupli[i*2,]$von_unix)==FALSE & is.na(tempo_frame_dupli[c((i*2)-1),]$bis_unix)==FALSE & tempo_frame_dupli[i*2,]$von_unix >= tempo_frame_dupli[c((i*2)-1),]$bis_unix){
				erroneous_duplications <- append(erroneous_duplications,0)
				}else{erroneous_duplications <- append(erroneous_duplications,1)
				}
		}

}


#---------------------------------------------------------------------------------------------------------------------------
#---------------------------------------------------------------------------------------------------------------------------

# Check data of individual parlamentarians (adapt ID in the code accordingly) manually: 

Parla_ID_DoubleEntries

#6  17  28  30  39  43  49  66  86 159 180 183 212 269 271 272 287 289 290 294 314

LobbyData <- fromJSON(paste0("http://lobbywatch.ch/de/data/interface/v1/json/table/parlamentarier/aggregated/id/", 314))

v <- LobbyData[["data"]][["interessenbindungen"]]$organisation_id
v

tempo_frame <- LobbyData[["data"]][["interessenbindungen"]][,c(43,44,54,55,56,57,58,59)]	
tempo_frame

dupli_vector <- LobbyData[["data"]][["interessenbindungen"]]$organisation_id[duplicated(LobbyData[["data"]][["interessenbindungen"]]$organisation_id)]
dupli_vector

tempo_frame_dupli <- data.frame(matrix(0,2*length(dupli_vector),dim(tempo_frame)[2]))
colnames(tempo_frame_dupli) <- colnames(tempo_frame)
tempo_frame_dupli

for(i in 1:length(dupli_vector)){
	tempo_frame_dupli[c((i*2)-1,i*2),] <- tempo_frame[tempo_frame$organisation_id==dupli_vector[i],]
}	

tempo_frame_dupli

#---------------------------------------------------------------------------------------------------------------------------

# Parlamentarier ID 6 / OrganisationsID 435: Freigabedatum unterschiedlich / "von, bis" keine Werte. 

#  parlamentarier_id organisation_id von bis      freigabe_datum bis_unix von_unix freigabe_datum_unix
#1                 6             435  NA  NA 2014-09-16 00:00:00       NA       NA          1410818400
#2                 6             435  NA  NA 2015-11-13 18:29:59       NA       NA          1447435799

#---------------------------------------------------------------------------------------------------------------------------

# Parlamentarier ID 17 / OrganisationsID 261: Freigabedatum gleich / "von,bis" keine Werte.

#  parlamentarier_id organisation_id  von bis      freigabe_datum bis_unix von_unix freigabe_datum_unix
#1                17             261 <NA>  NA 2014-09-16 00:00:00       NA     <NA>          1410818400
#2                17             261 <NA>  NA 2014-09-16 00:00:00       NA     <NA>          1410818400

#---------------------------------------------------------------------------------------------------------------------------

# Parlamentarier ID 28: Freigabedatum gleich / "von,bis" keine Werte.

#  parlamentarier_id organisation_id von bis      freigabe_datum bis_unix von_unix freigabe_datum_unix
#1                28             223  NA  NA 2014-09-16 00:00:00       NA       NA          1410818400
#2                28             223  NA  NA 2014-09-16 00:00:00       NA       NA          1410818400

#---------------------------------------------------------------------------------------------------------------------------

# Parlamentarier ID 30: Freigabedatum gleich / "von,bis" Werte unvollständig.

#  parlamentarier_id organisation_id        von bis      freigabe_datum bis_unix   von_unix freigabe_datum_unix
#1                30             621 2014-11-18  NA 2016-09-20 08:06:56       NA 1416265200          1474351616
#2                30             621       <NA>  NA 2016-09-20 08:06:56       NA       <NA>          1474351616

#---------------------------------------------------------------------------------------------------------------------------

# Parlamentarier ID 39: Freigabedatum gleich / "von,bis" keine Werte.

#  parlamentarier_id organisation_id von bis      freigabe_datum bis_unix von_unix freigabe_datum_unix
#1                39            2708  NA  NA 2016-09-03 20:52:05       NA       NA          1472928725
#2                39            2708  NA  NA 2016-09-03 20:52:05       NA       NA          1472928725
#3                39            2712  NA  NA 2016-09-03 20:52:05       NA       NA          1472928725
#4                39            2712  NA  NA 2016-09-03 20:52:05       NA       NA          1472928725


#---------------------------------------------------------------------------------------------------------------------------

# Parlamentarier ID 43: Freigabedatum gleich / "von,bis" keine Werte.

#  parlamentarier_id organisation_id von bis      freigabe_datum bis_unix von_unix freigabe_datum_unix
#1                43             814  NA  NA 2016-09-15 19:58:23       NA       NA          1473962303
#2                43             814  NA  NA 2016-09-15 19:58:23       NA       NA          1473962303


#---------------------------------------------------------------------------------------------------------------------------

# Parlamentarier ID 49: Freigabedatum gleich / "von,bis" keine Werte.

#  parlamentarier_id organisation_id von bis      freigabe_datum bis_unix von_unix freigabe_datum_unix
#1                49             732  NA  NA 2014-11-26 00:00:00       NA       NA          1416956400
#2                49             732  NA  NA 2014-11-26 00:00:00       NA       NA          1416956400

#---------------------------------------------------------------------------------------------------------------------------

# Parlamentarier ID 66: Freigabedatum gleich / "von,bis" keine Werte.

#  parlamentarier_id organisation_id  von bis      freigabe_datum bis_unix von_unix freigabe_datum_unix
#1                66             453 <NA>  NA 2015-03-21 00:00:00       NA     <NA>          1426892400
#2                66             453 <NA>  NA 2015-03-21 00:00:00       NA     <NA>          1426892400

#---------------------------------------------------------------------------------------------------------------------------

# Parlamentarier ID 86: Freigabedatum gleich / "von,bis" Werte unvollständig.

#  parlamentarier_id organisation_id        von bis      freigabe_datum bis_unix   von_unix freigabe_datum_unix
#1                86            2305 2007-07-05  NA 2016-07-31 17:21:58       NA 1183586400          1469978518
#2                86            2305       <NA>  NA 2016-07-31 17:21:58       NA       <NA>          1469978518

#---------------------------------------------------------------------------------------------------------------------------

# Parlamentarier ID 159: Freigabedatum gleich / "von,bis" keine Werte.

#  parlamentarier_id organisation_id von bis      freigabe_datum bis_unix von_unix freigabe_datum_unix
#1               159             140  NA  NA 2014-11-26 00:00:00       NA       NA          1416956400
#2               159             140  NA  NA 2014-11-26 00:00:00       NA       NA          1416956400

#---------------------------------------------------------------------------------------------------------------------------

# Parlamentarier ID 180: Freigabedatum unterschiedlich / "von,bis" keine Werte.

#  parlamentarier_id organisation_id  von bis      freigabe_datum bis_unix von_unix freigabe_datum_unix
#1               180            2714 <NA>  NA 2015-11-06 07:07:56       NA     <NA>          1446790076
#2               180            2714 <NA>  NA 2015-11-06 07:08:48       NA     <NA>          1446790128

#---------------------------------------------------------------------------------------------------------------------------

# Parlamentarier ID 183: Freigabedatum gleich / "von,bis" keine Werte.

#  parlamentarier_id organisation_id von bis      freigabe_datum bis_unix von_unix freigabe_datum_unix
#1               183            2576  NA  NA 2015-12-07 00:00:00       NA       NA          1449442800
#2               183            2576  NA  NA 2015-12-07 00:00:00       NA       NA          1449442800

#---------------------------------------------------------------------------------------------------------------------------

# Parlamentarier ID 212: Freigabedatum gleich / "von,bis" keine Werte.

#  parlamentarier_id organisation_id von bis      freigabe_datum bis_unix von_unix freigabe_datum_unix
#1               212             244  NA  NA 2014-11-26 00:00:00       NA       NA          1416956400
#2               212             244  NA  NA 2014-11-26 00:00:00       NA       NA          1416956400

#---------------------------------------------------------------------------------------------------------------------------

# Parlamentarier ID 269: Freigabedatum gleich / "von,bis" Werte unvollständig.

#  parlamentarier_id organisation_id        von bis      freigabe_datum bis_unix   von_unix freigabe_datum_unix
#1               269            2335 2002-04-09  NA 2016-07-28 12:07:11       NA 1018303200          1469700431
#2               269            2335       <NA>  NA 2016-07-28 12:07:11       NA       <NA>          1469700431

#---------------------------------------------------------------------------------------------------------------------------

# Parlamentarier ID 271: Freigabedatum gleich / "von,bis" Werte unvollständig.

#  parlamentarier_id organisation_id  von bis      freigabe_datum bis_unix von_unix freigabe_datum_unix
#1               271            2714 <NA>  NA 2016-02-04 00:00:00       NA     <NA>          1454540400
#2               271            2714 <NA>  NA 2016-02-04 00:00:00       NA     <NA>          1454540400

#---------------------------------------------------------------------------------------------------------------------------

# Parlamentarier ID 272: Freigabedatum gleich / "von,bis" keine Werte.

#  parlamentarier_id organisation_id von bis      freigabe_datum bis_unix von_unix freigabe_datum_unix
#1               272            2947  NA  NA 2016-03-21 00:00:00       NA       NA          1458514800
#2               272            2947  NA  NA 2016-03-21 00:00:00       NA       NA          1458514800
#3               272            2946  NA  NA 2016-03-21 00:00:00       NA       NA          1458514800
#4               272            2946  NA  NA 2016-03-21 00:00:00       NA       NA          1458514800
#5               272            2945  NA  NA 2016-03-21 00:00:00       NA       NA          1458514800
#6               272            2945  NA  NA 2016-03-21 00:00:00       NA       NA          1458514800

#---------------------------------------------------------------------------------------------------------------------------

# Parlamentarier ID 287: Freigabedatum gleich / "von,bis" keine Werte.

#  parlamentarier_id organisation_id von bis      freigabe_datum bis_unix von_unix freigabe_datum_unix
#1               287            2714  NA  NA 2016-07-19 07:12:17       NA       NA          1468905137
#2               287            2714  NA  NA 2016-07-19 07:12:17       NA       NA          1468905137

#---------------------------------------------------------------------------------------------------------------------------

# Parlamentarier ID 289: Freigabedatum gleich / "von,bis" keine Werte.

#  parlamentarier_id organisation_id von bis      freigabe_datum bis_unix von_unix freigabe_datum_unix
#1               289            3109  NA  NA 2016-07-23 18:17:18       NA       NA          1469290638
#2               289            3109  NA  NA 2016-07-23 18:17:18       NA       NA          1469290638

#---------------------------------------------------------------------------------------------------------------------------

# Parlamentarier ID 290: Freigabedatum gleich / "von,bis" keine Werte.

#  parlamentarier_id organisation_id von bis      freigabe_datum bis_unix von_unix freigabe_datum_unix
#1               290            2933  NA  NA 2016-07-30 20:39:12       NA       NA          1469903952
#2               290            2933  NA  NA 2016-07-30 20:39:12       NA       NA          1469903952

#---------------------------------------------------------------------------------------------------------------------------

# Parlamentarier ID 294: Freigabedatum gleich / "von,bis" keine Werte.

#  parlamentarier_id organisation_id von bis      freigabe_datum bis_unix von_unix freigabe_datum_unix
#1               294            3398  NA  NA 2016-07-04 18:25:21       NA       NA          1467649521
#2               294            3398  NA  NA 2016-07-04 18:25:21       NA       NA          1467649521

#---------------------------------------------------------------------------------------------------------------------------

# Parlamentarier ID 314: Freigabedatum gleich / "von,bis" keine Werte.

#  parlamentarier_id organisation_id von bis      freigabe_datum bis_unix von_unix freigabe_datum_unix
#1               314            3122  NA  NA 2016-07-30 18:41:32       NA       NA          1469896892
#2               314            3122  NA  NA 2016-07-30 18:41:32       NA       NA          1469896892


